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
 * TenantContext 租户上下文
 * 管理当前请求的租户状态
 */
namespace BoxPHP\Tenant\Tenant;

class TenantContext
{
    protected static ?TenantInterface $tenant = null;

    public static function set(TenantInterface $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function get(): ?TenantInterface
    {
        return self::$tenant;
    }

    public static function getId(): ?string
    {
        return self::$tenant?->getId();
    }

    public static function clear(): void
    {
        self::$tenant = null;
    }

    public static function has(): bool
    {
        return self::$tenant !== null;
    }

    /**
     * 获取租户设置
     */
    public static function getSetting(string $key, mixed $default = null): mixed
    {
        return self::$tenant?->getSetting($key, $default) ?? $default;
    }
}
