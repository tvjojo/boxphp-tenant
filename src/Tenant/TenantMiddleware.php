<?php
/**
 * TenantMiddleware 租户中间件
 */
namespace BoxPHP\Tenant\Tenant;

use BoxPHP\Core\Middleware\MiddlewareInterface;
use BoxPHP\Server\Http\Message\HttpResponse;

class TenantMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected TenantResolver $resolver
    ) {}

    public function handle(mixed $request, callable $next): mixed
    {
        $host = $request['headers']['host'] ?? '';
        $path = $request['path'] ?? '/';

        // 优先子域名，其次路径
        $tenant = $this->resolver->resolveByDomain($host);
        if ($tenant === null) {
            $tenant = $this->resolver->resolveFromPath($path);
        }

        if ($tenant === null) {
            return HttpResponse::error(404, 'Tenant not found');
        }

        if (!$tenant->isActive()) {
            return HttpResponse::error(403, 'Tenant is disabled');
        }

        $this->resolver->setCurrent($tenant);
        $request['tenant'] = $tenant;
        $request['tenant_id'] = $tenant->getId();

        return $next($request);
    }
}
