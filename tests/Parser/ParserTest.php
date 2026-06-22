<?php

/**
 * Copyright 2026 Aleksandar Panic
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

namespace tests\Parser;


use ArekX\RestFn\App\WebApp;
use ArekX\RestFn\DI\Container;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Exceptions\InvalidOperation;
use ArekX\RestFn\Parser\Exceptions\InvalidValueFormatException;
use ArekX\RestFn\Parser\Exceptions\MaxDepthExceededException;
use ArekX\RestFn\Parser\Ops\AndOp;
use ArekX\RestFn\Parser\Ops\CastOp;
use ArekX\RestFn\Parser\Ops\CoalesceOp;
use ArekX\RestFn\Parser\Ops\CompareOp;
use ArekX\RestFn\Parser\Ops\GetOp;
use ArekX\RestFn\Parser\Ops\IfElseOp;
use ArekX\RestFn\Parser\Ops\ListOp;
use ArekX\RestFn\Parser\Ops\MapOp;
use ArekX\RestFn\Parser\Ops\MergeOp;
use ArekX\RestFn\Parser\Ops\NotOp;
use ArekX\RestFn\Parser\Ops\ObjectOp;
use ArekX\RestFn\Parser\Ops\OrOp;
use ArekX\RestFn\Parser\Ops\RunOp;
use ArekX\RestFn\Parser\Ops\SequenceOp;
use ArekX\RestFn\Parser\Ops\SortOp;
use ArekX\RestFn\Parser\Ops\TakeOp;
use ArekX\RestFn\Parser\Ops\ValueOp;
use ArekX\RestFn\Parser\Ops\VarOp;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyNestedOperation;
use tests\Parser\_mock\DummyOperation;
use tests\TestCase;

class ParserTest extends TestCase
{
    public function testEmptyArrayPassedToRule()
    {
        $parser = $this->getParser();

        $this->assertEquals([], $parser->evaluate([], new Context()));
    }

    public function testInvalidRulePassedToParser()
    {
        $parser = $this->getParser();

        $this->expectException(InvalidValueFormatException::class);

        $parser->evaluate('invalidrule', new Context());
    }

    public function testInvalidOperationThrowsException()
    {
        $parser = $this->getParser();

        $this->expectException(InvalidOperation::class);

        $parser->evaluate(['test'], new Context());
    }

    public function testParserDispatchesOperationsByConfiguredName()
    {
        // Operations are configured as a name => class map and dispatched by name.
        $parser = $this->makeParser([DummyOperation::class]);

        $this->assertEquals(1, $parser->evaluate([DummyOperation::name()], new Context()));
    }

    public function testParserRejectsUnknownOperationName()
    {
        $parser = $this->makeParser([DummyOperation::class]);

        $this->expectException(InvalidOperation::class);

        $parser->evaluate(['unregistered'], new Context());
    }

    public function testOperationIsEvaluated()
    {
        $parser = $this->getParser([
            DummyOperation::class
        ]);

        $this->assertEquals(1, $parser->evaluate(['test'], new Context()));
    }

    public function testResolveReturnsLiteralsAndEvaluatesExpressions()
    {
        $parser = $this->getParser([DummyOperation::class]);
        $context = new Context();

        // Non-array values are literals, returned unchanged.
        $this->assertSame('literal', $parser->resolve('literal', $context));
        $this->assertSame(7, $parser->resolve(7, $context));

        // Arrays are sub-expressions and get evaluated.
        $this->assertSame(1, $parser->resolve(['test'], $context));
    }

    public function testNestedOperationsIsEvaluated()
    {
        $parser = $this->getParser([
            DummyNestedOperation::class,
            DummyOperation::class
        ]);

        $this->assertEquals('nested-1', $parser->evaluate(['nested', ['test']], new Context()));
    }


    public function testValidateEmpty()
    {
        $parser = $this->getParser([
            DummyOperation::class
        ]);

        $this->assertEquals(null, $parser->validate([], new Context()));
    }

    public function testValidateSuccess()
    {
        $parser = $this->getParser([
            DummyOperation::class
        ]);

        $this->assertEquals(null, $parser->validate(['test'], new Context()));
    }

    public function testValidateFail()
    {
        $parser = $this->getParser([DummyFailOperation::class]);

        $this->assertEquals([DummyFailOperation::name(), ['failed' => true]], $parser->validate([DummyFailOperation::name()], new Context()));
    }

    public function getParser($ops = [], array $parserConfig = [])
    {
        return $this->makeParser($ops, $parserConfig);
    }

    public function testInvalidValidationError()
    {
        $parser = $this->getParser();
        $this->expectException(InvalidValueFormatException::class);

        $parser->validate('invalidrule', new Context());
    }

    public function testInvalidOpValidation()
    {
        $parser = $this->getParser();

        $this->expectException(InvalidOperation::class);

        $this->assertEquals(null, $parser->validate(['test'], new Context()));
    }

    public function testNestedValidationFail()
    {
        $parser = $this->getParser([
            DummyNestedOperation::class,
            DummyFailOperation::class
        ]);

        $this->assertEquals(
            [DummyNestedOperation::name(), [DummyFailOperation::name(), ['failed' => true]]],
            $parser->validate([DummyNestedOperation::name(), [DummyFailOperation::name()]], new Context())
        );
    }

    public function testNestedValidationSuccess()
    {
        $parser = $this->getParser([
            DummyNestedOperation::class,
            DummyOperation::class
        ]);

        $this->assertEquals(null, $parser->validate(['nested', ['test']], new Context()));
    }

    public function testEvaluateWithinMaxDepthSucceeds()
    {
        $parser = $this->getParser(
            [DummyNestedOperation::class, DummyOperation::class],
            ['limits' => ['maxDepth' => 3]]
        );

        // ['nested', ['nested', ['test']]] is exactly 3 levels deep.
        $this->assertEquals('nested-nested-1', $parser->evaluate($this->buildNested(2), new Context()));
    }

    public function testEvaluateExceedingMaxDepthThrows()
    {
        $parser = $this->getParser(
            [DummyNestedOperation::class, DummyOperation::class],
            ['limits' => ['maxDepth' => 3]]
        );

        $this->expectException(MaxDepthExceededException::class);

        $parser->evaluate($this->buildNested(3), new Context());
    }

    public function testValidateExceedingMaxDepthThrows()
    {
        $parser = $this->getParser(
            [DummyNestedOperation::class, DummyOperation::class],
            ['limits' => ['maxDepth' => 3]]
        );

        $this->expectException(MaxDepthExceededException::class);

        $parser->validate($this->buildNested(3), new Context());
    }

    /**
     * Builds an expression nested $levels deep around a leaf ['test'] operation.
     */
    protected function buildNested(int $levels): array
    {
        $expression = ['test'];

        for ($i = 0; $i < $levels; $i++) {
            $expression = ['nested', $expression];
        }

        return $expression;
    }

    public function testBuiltInOperationsAreSharedInstances()
    {
        $container = new Container([
            'aliases' => WebApp::DEFAULT_ALIASES,
        ]);

        // Operations are stateless (all per-evaluation state lives in the Context),
        // so the container shares a single instance of each across the request.
        $this->assertSame($container->make(AndOp::class), $container->make(AndOp::class));
        $this->assertSame($container->make(RunOp::class), $container->make(RunOp::class));
        $this->assertSame($container->make(ValueOp::class), $container->make(ValueOp::class));
    }

    public function testBuiltInOpNamesAreUnique()
    {
        $ops = [
            AndOp::class,
            CastOp::class,
            CoalesceOp::class,
            CompareOp::class,
            GetOp::class,
            IfElseOp::class,
            ListOp::class,
            MapOp::class,
            MergeOp::class,
            NotOp::class,
            ObjectOp::class,
            OrOp::class,
            RunOp::class,
            SequenceOp::class,
            SortOp::class,
            TakeOp::class,
            ValueOp::class,
            VarOp::class,
        ];

        $names = array_map(static fn(string $op) => $op::name(), $ops);

        $duplicates = array_keys(array_filter(array_count_values($names), static fn(int $count) => $count > 1));

        $this->assertSame([], $duplicates, 'Built-in operations must have unique names.');
        $this->assertCount(count($ops), array_unique($names));
    }
}
