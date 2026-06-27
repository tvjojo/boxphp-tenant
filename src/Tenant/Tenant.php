<?php
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
