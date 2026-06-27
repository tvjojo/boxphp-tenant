<?php
/**
 * Tenant 包测试 - 修正版
 */
require_once __DIR__ . '/../vendor/autoload.php';

use BoxPHP\Tenant\Tenant\Tenant;
use BoxPHP\Tenant\Tenant\TenantContext;

echo "=== BoxPHP Tenant Package Tests ===\n\n";
$passed = 0;
$failed = 0;

// Test 1: Tenant Entity
echo "1. Tenant Entity\n";
try {
    $tenant = new Tenant(
        'tenant_1',
        'Acme Corp',
        'acme.example.com',
        [],
        [],
        ['plan' => 'pro', 'max_users' => 100],
        true
    );
    
    assert($tenant->getId() === 'tenant_1');
    assert($tenant->getName() === 'Acme Corp');
    assert($tenant->getDomain() === 'acme.example.com');
    assert($tenant->getSetting('plan') === 'pro');
    assert($tenant->getSetting('max_users') === 100);
    assert($tenant->getSetting('nonexistent', 'default') === 'default');
    assert($tenant->isActive() === true);
    
    // to array
    $array = $tenant->toArray();
    assert($array['id'] === 'tenant_1');
    assert($array['name'] === 'Acme Corp');
    assert($array['domain'] === 'acme.example.com');
    assert($array['active'] === true);
    
    echo "   ✓ Tenant entity tests passed\n";
    $passed++;
} catch (\Throwable $e) {
    echo "   ✗ Failed: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 2: Tenant fromArray
echo "2. Tenant fromArray\n";
try {
    $tenant = Tenant::fromArray([
        'id' => 'tenant_2',
        'name' => 'Bob Corp',
        'domain' => 'bob.example.com',
        'settings' => ['plan' => 'basic'],
    ]);
    
    assert($tenant->getId() === 'tenant_2');
    assert($tenant->getName() === 'Bob Corp');
    assert($tenant->getDomain() === 'bob.example.com');
    assert($tenant->getSetting('plan') === 'basic');
    
    echo "   ✓ Tenant fromArray tests passed\n";
    $passed++;
} catch (\Throwable $e) {
    echo "   ✗ Failed: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 3: Tenant Context
echo "3. Tenant Context\n";
try {
    // Clear first
    TenantContext::clear();
    assert(TenantContext::get() === null);
    
    $tenant1 = new Tenant('tenant_1', 'Tenant 1', 't1.example.com');
    TenantContext::set($tenant1);
    assert(TenantContext::get() === $tenant1);
    assert(TenantContext::getId() === 'tenant_1');
    
    $tenant2 = new Tenant('tenant_2', 'Tenant 2', 't2.example.com');
    TenantContext::set($tenant2);
    assert(TenantContext::get() === $tenant2);
    assert(TenantContext::getId() === 'tenant_2');
    
    TenantContext::clear();
    assert(TenantContext::get() === null);
    
    echo "   ✓ Tenant context tests passed\n";
    $passed++;
} catch (\Throwable $e) {
    echo "   ✗ Failed: " . $e->getMessage() . "\n";
    $failed++;
}

echo "\n=== Results: {$passed} passed, {$failed} failed ===\n";
exit($failed > 0 ? 1 : 0);
