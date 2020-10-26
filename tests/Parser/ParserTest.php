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
use ArekX\RestFn\Parser\Exceptions\InvalidRuleFormat;
use ArekX\RestFn\Parser\Parser;
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
        $parser->setContext($context);
        $this->assertSame($context, $parser->getContext());
    }

    public function testNoSetContextReturnsEmptyArray()
    {
        $this->assertSame([], (new Parser())->getContext());
    }


    public function testEmptyArrayPassedToRule()
    {
        $parser = new Parser();
        $parser->ops = [];

        $this->expectException(InvalidOperation::class);

        $parser->evaluate([], null);
    }

    public function testInvalidRulePassedToParser()
    {
        $parser = new Parser();
        $parser->ops = [];

        $this->expectException(InvalidRuleFormat::class);

        $parser->evaluate('invalidrule', null);
    }

    public function testInvalidOperationThrowsException()
    {
        $parser = new Parser();
        $parser->ops = [];

        $this->expectException(InvalidOperation::class);

        $parser->evaluate(['test'], null);
    }

    public function testParserConfigure()
    {
        $parser = new Parser();
        $parser->configure([
            'ops' => [
                'test' => DummyOperation::class
            ]
        ]);

        $this->assertEquals(1, $parser->evaluate(['test'], null));
    }

    public function testOperationIsEvaluated()
    {
        $parser = new Parser();
        $parser->ops = [
            'test' => DummyOperation::class
        ];

        $this->assertEquals(1, $parser->evaluate(['test'], null));
    }

    public function testNestedOperationsIsEvaluated()
    {
        $parser = new Parser();
        $parser->ops = [
            'nested' => DummyNestedOperation::class,
            'test' => DummyOperation::class
        ];


        $this->assertEquals('nested-1', $parser->evaluate(['nested', ['test']], null));
    }


    public function testValidateEmpty()
    {
        $parser = new Parser();
        $parser->ops = [];

        $this->assertEquals(null, $parser->validate(['nested', ['test']], null));
    }
}