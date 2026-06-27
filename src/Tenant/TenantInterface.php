<?php
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
