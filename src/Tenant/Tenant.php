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
 * Tenant 租户实体
 */
namespace BoxPHP\Tenant\Tenant;

class Tenant implements TenantInterface
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected string $domain,
        protected array $dbConfig = [],
        protected array $redisConfig = [],
        protected array $settings = [],
        protected bool $active = true
    ) {}

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDomain(): string { return $this->domain; }
    public function getDbConfig(): array { return $this->dbConfig; }
    public function getRedisConfig(): array { return $this->redisConfig; }
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }
    public function isActive(): bool { return $this->active; }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['id'] ?? uniqid('tenant_'),
            $data['name'] ?? '',
            $data['domain'] ?? '',
            $data['db'] ?? [],
            $data['redis'] ?? [],
            $data['settings'] ?? [],
            $data['active'] ?? true
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'domain' => $this->domain,
            'db' => $this->dbConfig,
            'redis' => $this->redisConfig,
            'settings' => $this->settings,
            'active' => $this->active,
        ];
    }
}
