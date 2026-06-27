<?php
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
