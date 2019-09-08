<?php

namespace Tests\Feature\Middleware;

use App\User;
use App\Domain;
use App\Tenant;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_with_selected_domains_are_not_redirected()
    {
        $tenant = factory(Tenant::class)->create();
        $domain = factory(Domain::class)->create([
            'name' => 'original',
            'tenant_id' => $tenant->id,
        ]);
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $middleware = new CheckDomain();
        $request = Request::create('http://localhost/home', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $middleware->handle($request, function () {
        });

        $this->assertNull($response);
    }

    public function test_users_without_an_active_domain_will_have_one_selected_for_them()
    {
        $tenant = factory(Tenant::class)->create();
        $domain = factory(Domain::class)->create([
            'name' => 'original',
            'tenant_id' => $tenant->id,
        ]);
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => null,
        ]);
        $middleware = new CheckDomain();
        $request = Request::create('http://localhost/home', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $middleware->handle($request, function () {
        });

        $this->assertNull($response);
        $this->assertEquals($domain->id, $user->fresh()->current_domain_id);
    }

    public function test_users_with_no_available_domains_are_redirected()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $middleware = new CheckDomain();
        $request = Request::create('http://localhost/home', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
        $this->assertEquals('http://localhost/domains/create', $response->getTargetUrl());
    }
}
