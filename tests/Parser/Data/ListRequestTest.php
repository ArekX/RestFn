<?php
/**
 * Copyright 2025 Aleksandar Panic
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

namespace tests\Parser\Data;


use ArekX\RestFn\Parser\Data\ListRequest;
use tests\TestCase;

class ListRequestTest extends TestCase
{
    public function testEmptyRequestData()
    {
        $request = new ListRequest([]);

        $this->assertEquals(0, $request->getPage());
        $this->assertEquals(1, $request->getPageSize());
        $this->assertEquals(false, $request->hasProperties());
        $this->assertEquals([], $request->getProperties());
        $this->assertEquals([], $request->getFilters());
    }

    public function testRequestData()
    {
        $request = new ListRequest([
            'page' => 5,
            'pageSize' => 20,
            'filters' => ['test' => 1],
            'properties' => ['test']
        ]);

        $this->assertEquals(5, $request->getPage());
        $this->assertEquals(20, $request->getPageSize());
        $this->assertEquals(true, $request->hasProperties());
        $this->assertEquals(['test'], $request->getProperties());
        $this->assertEquals(['test' => 1], $request->getFilters());
    }

    public function testValidation()
    {
        $request = new ListRequest([
            'page' => -153,
            'pageSize' => -2525,
        ]);

        $this->assertEquals(0, $request->getPage());
        $this->assertEquals(1, $request->getPageSize());
        $this->assertEquals(false, $request->hasProperties());
        $this->assertEquals([], $request->getProperties());
        $this->assertEquals([], $request->getFilters());
    }
}