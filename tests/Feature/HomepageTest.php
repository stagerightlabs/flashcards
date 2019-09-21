<?php

namespace Tests\Feature;

use App\User;
use App\Domain;
use App\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_users_cannot_reach_the_home_page()
    {
        $request = $this->get(route('home'));

        $request->assertRedirect(route('welcome'));
    }

    public function test_authenticated_users_can_reach_the_home_page()
    {
        $tenant = factory(Tenant::class)->create();
        $domain = factory(Domain::class)->create([
            'tenant_id' => $tenant,
        ]);
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $this->actingAs($user);

        $request = $this->get(route('home'));

        $request->assertStatus(200);
        $request->assertViewIs('home');
    }
}
