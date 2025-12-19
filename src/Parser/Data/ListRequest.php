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

namespace ArekX\RestFn\Parser\Data;


class ListRequest
{
    protected int $page;
    protected int $pageSize;
    protected array $filters;
    protected array $properties;

    public function __construct(array $request)
    {
        $this->page = max(0, $request['page'] ?? 0);
        $this->pageSize = max(1, $request['pageSize'] ?? 1);
        $this->filters = $request['filters'] ?? [];
        $this->properties = $request['properties'] ?? [];
    }

    public function hasProperties()
    {
        return !empty($this->properties);
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}