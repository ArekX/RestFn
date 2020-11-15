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

namespace tests\Helper;


use ArekX\RestFn\Helper\Value;
use tests\TestCase;

class ValueTest extends TestCase
{
    public function testEvaluateDirectName()
    {

        $this->assertEquals('value1', Value::get('path.to.item', [
            'path.to.item' => 'value1',
            'path' => [
                'to' => [
                    'item' => 'value2'
                ]
            ]
        ]));
    }

    public function testWalkThroughArray()
    {
        $this->assertEquals('value', Value::get('path.to.item', [
            'path' => [
                'to' => [
                    'item' => 'value'
                ]
            ]
        ]));
    }

    public function testNonExistentPath()
    {
        $this->assertEquals(null, Value::get('invalid.path', [
            'path' => [
                'to' => [
                    'item' => 'value'
                ]
            ]
        ]));
    }

    public function testPathThroughNonArray()
    {
        $this->assertEquals(null, Value::get('path.to.item.value', [
            'path' => [
                'to' => 'item.value'
            ]
        ]));
    }

    public function testPathThroughNonArrayForLastKey()
    {
        $this->assertEquals(null, Value::get('path.to.item.value', [
            'path' => [
                'to' => [
                    'item' => 'value'
                ]
            ]
        ]));
    }

    public function testDefaultValue()
    {
        $this->assertEquals('default', Value::get('non.existing.path', [
            'path' => [
                'to' => 'value'
            ]
        ], 'default'));
    }

    public function testNonExistingKey()
    {
        $this->assertEquals(null, Value::get('non.existing.path', [
            'path' => [
                'to' => 'value'
            ]
        ]));
    }


    public function testInvalidParams()
    {
        $this->assertNull(Value::get('path..to', [
            'path' => [
                'to' => 'value'
            ]
        ]));

        $this->assertNull(Value::get('path. to', [
            'path' => [
                'to' => 'value'
            ]
        ]));

        $this->assertNull(Value::get('path.to.', [
            'path' => [
                'to' => 'value'
            ]
        ]));

        $this->assertNull(Value::get('....', [
            'path' => [
                'to' => 'value'
            ]
        ]));

        $this->assertNull(Value::get('-123', [
            'path' => [
                'to' => 'value'
            ]
        ]));
    }
}