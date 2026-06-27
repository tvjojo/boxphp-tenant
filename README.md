# boxphp/tenant

BoxPHP 多租户包 - SaaS 多租户支持

## 安装

```bash
composer require boxphp/tenant
```

## 使用

### 租户实体

```php
use BoxPHP\Tenant\Tenant\Tenant;

$tenant = new Tenant([
    'id' => 'tenant_1',
    'name' => 'Acme Corp',
    'domain' => 'acme.example.com',
    'settings' => ['plan' => 'pro'],
]);

echo $tenant->getId();      // tenant_1
echo $tenant->getName();     // Acme Corp
echo $tenant->getDomain();   // acme.example.com
```

### 租户解析器

```php
use BoxPHP\Tenant\Tenant\TenantResolver;

$resolver = new TenantResolver();

// 从域名解析
$tenant = $resolver->resolveByDomain('acme.example.com');

// 从路径解析
$tenant = $resolver->resolveByPath('/tenant_1/dashboard');

// 从 header 解析
$tenant = $resolver->resolveByHeader(['X-Tenant-ID' => 'tenant_1']);
```

### 租户上下文

```php
use BoxPHP\Tenant\Tenant\TenantContext;

// 设置当前租户
TenantContext::set($tenant);

// 获取当前租户
$current = TenantContext::get();

// 清除
TenantContext::clear();
```

### 租户中间件

```php
use BoxPHP\Tenant\Tenant\TenantMiddleware;
use BoxPHP\Server\Http\Server\HttpServer;

$server = new HttpServer('http://0.0.0.0:8080');

// 自动解析租户
$server->get('/api/{path}', function ($request) {
    $tenant = $request['tenant'];
    return HttpResponse::json(['tenant' => $tenant->getName()]);
})->middleware(new TenantMiddleware());
```

### 自定义租户解析

```php
$resolver = new TenantResolver();

// 添加自定义解析逻辑
$resolver->addResolver('api_key', function ($request) {
    $apiKey = $request['headers']['x-api-key'] ?? null;
    if ($apiKey) {
        return $this->findByApiKey($apiKey);
    }
    return null;
});
```

## 依赖

- PHP >= 8.1
- boxphp/core ^1.0
- boxphp/server ^1.0
