<?php
/**
 * Copyright 2020 Aleksandar Panic
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


use ArekX\RestFn\Parser\Exceptions\InvalidOperation;
use ArekX\RestFn\Parser\Exceptions\InvalidValueFormatException;
use ArekX\RestFn\Parser\Parser;
use tests\Parser\_mock\DummyFailOperation;
use tests\Parser\_mock\DummyNestedOperation;
use tests\Parser\_mock\DummyOperation;
use tests\TestCase;

class ParserTest extends TestCase
{
    public function testContextSuccessfullySet()
    {
        $context = [
            'test' => 'context',
            'random' => rand(1, 555)
        ];

        $parser = new Parser();
        $parser->setContext('testContext', $context);
        $this->assertSame($context, $parser->getContext('testContext'));
    }

    public function testNoSetContextReturnsEmptyArray()
    {
        $this->assertSame(null, (new Parser())->getContext('testContext'));
    }


    public function testEmptyArrayPassedToRule()
    {
        $parser = $this->getParser();

        $this->assertEquals([], $parser->evaluate([]));
    }

    public function testInvalidRulePassedToParser()
    {
        $parser = $this->getParser();

        $this->expectException(InvalidValueFormatException::class);

        $parser->evaluate('invalidrule');
    }

    public function testInvalidOperationThrowsException()
    {
        $parser = $this->getParser();

        $this->expectException(InvalidOperation::class);

        $parser->evaluate(['test']);
    }

    public function testParserConfigure()
    {
        $ops = [
            DummyOperation::name() => DummyOperation::class
        ];

        $parser = new Parser();
        $parser->injector = $this->getInjector();
        $parser->configure([
            'ops' => [DummyOperation::class]
        ]);

        $this->assertEquals($ops, $parser->ops);
        $this->assertEquals(1, $parser->evaluate(['test']));
    }

    public function testOperationIsEvaluated()
    {
        $parser = $this->getParser([
            DummyOperation::class
        ]);

        $this->assertEquals(1, $parser->evaluate(['test']));
    }

    public function testNestedOperationsIsEvaluated()
    {
        $parser = $this->getParser([
            DummyNestedOperation::class,
            DummyOperation::class
        ]);

        $this->assertEquals('nested-1', $parser->evaluate(['nested', ['test']]));
    }


    public function testValidateEmpty()
    {
        $parser = $this->getParser([
            DummyOperation::class
        ]);

        $this->assertEquals(null, $parser->validate([]));
    }

    public function testValidateSuccess()
    {
        $parser = $this->getParser([
            DummyOperation::class
        ]);

        $this->assertEquals(null, $parser->validate(['test']));
    }

    public function testValidateFail()
    {
        $parser = $this->getParser([DummyFailOperation::class]);

        $this->assertEquals([DummyFailOperation::name(), ['failed' => true]], $parser->validate([DummyFailOperation::name()]));
    }

    public function getParser($ops = [])
    {
        $parser = new Parser();
        $parser->injector = $this->getInjector();

        $parser->configure(['ops' => $ops]);
        return $parser;
    }

    public function testInvalidValidationError()
    {
        $parser = $this->getParser();
        $this->expectException(InvalidValueFormatException::class);

        $parser->validate('invalidrule');
    }

    public function testInvalidOpValidation()
    {
        $parser = $this->getParser();

        $this->expectException(InvalidOperation::class);

        $this->assertEquals(null, $parser->validate(['test']));
    }

    public function testNestedValidationFail()
    {
        $parser = $this->getParser([
            DummyNestedOperation::class,
            DummyFailOperation::class
        ]);

        $this->assertEquals(
            [DummyNestedOperation::name(), [DummyFailOperation::name(), ['failed' => true]]],
            $parser->validate([DummyNestedOperation::name(), [DummyFailOperation::name()]])
        );
    }

    public function testNestedValidationSuccess()
    {
        $parser = $this->getParser([
            DummyNestedOperation::class,
            DummyOperation::class
        ]);

        $this->assertEquals(null, $parser->validate(['nested', ['test']]));
    }
}