<?php
/**
 * BoxPHP Framework
 *
 * Copyright 2026 BoxPHP
 * By tvjojo, asterhuang, 黄波涛; 5viv.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * TenantInterface 租户接口
 */
namespace BoxPHP\Tenant\Tenant;

interface TenantInterface
{
    public function getId(): string;
    public function getName(): string;
    public function getDomain(): string;
    public function getDbConfig(): array;
    public function getRedisConfig(): array;
    public function getSetting(string $key, mixed $default = null): mixed;
    public function isActive(): bool;
}
