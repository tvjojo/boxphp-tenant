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
