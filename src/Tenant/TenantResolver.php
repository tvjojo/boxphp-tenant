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
 * TenantResolver 租户解析器
 */
namespace BoxPHP\Tenant\Tenant;

class TenantResolver
{
    /** @var array<string, TenantInterface> 租户缓存 */
    protected array $tenants = [];

    /** @var callable|null 自定义解析回调 */
    protected $resolver = null;

    /** @var TenantInterface|null 当前租户 */
    protected ?TenantInterface $current = null;

    /**
     * @param callable|null $resolver 解析回调: function(string $identifier): ?TenantInterface
     */
    public function __construct(?callable $resolver = null)
    {
        $this->resolver = $resolver;
    }

    /**
     * 通过域名解析租户
     */
    public function resolveByDomain(string $domain): ?TenantInterface
    {
        return $this->resolve($domain);
    }

    /**
     * 通过 ID 解析租户
     */
    public function resolveById(string $id): ?TenantInterface
    {
        return $this->resolve($id);
    }

    /**
     * 通过请求路径解析租户
     * 支持: /tenant/{id}/... 或 子域名方式
     */
    public function resolveFromPath(string $path): ?TenantInterface
    {
        if (preg_match('#^/tenant/([^/]+)#', $path, $matches)) {
            return $this->resolve($matches[1]);
        }
        return null;
    }

    /**
     * 通用解析
     */
    public function resolve(string $identifier): ?TenantInterface
    {
        // 从缓存获取
        if (isset($this->tenants[$identifier])) {
            return $this->tenants[$identifier];
        }

        // 自定义解析
        if ($this->resolver !== null) {
            $tenant = ($this->resolver)($identifier);
            if ($tenant !== null) {
                $this->tenants[$identifier] = $tenant;
                return $tenant;
            }
        }

        return null;
    }

    /**
     * 设置当前租户
     */
    public function setCurrent(TenantInterface $tenant): void
    {
        $this->current = $tenant;
    }

    /**
     * 获取当前租户
     */
    public function getCurrent(): ?TenantInterface
    {
        return $this->current;
    }

    /**
     * 清除当前租户
     */
    public function clearCurrent(): void
    {
        $this->current = null;
    }

    /**
     * 注册租户
     */
    public function register(TenantInterface $tenant): void
    {
        $this->tenants[$tenant->getId()] = $tenant;
        $this->tenants[$tenant->getDomain()] = $tenant;
    }

    /**
     * 批量注册
     */
    public function registerMany(array $tenants): void
    {
        foreach ($tenants as $tenant) {
            $this->register($tenant);
        }
    }

    /**
     * 获取所有租户
     */
    public function getAll(): array
    {
        return $this->tenants;
    }
}
