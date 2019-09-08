<?php

namespace Tests\Feature;

use App\Card;
use App\User;
use App\Domain;
use App\Tenant;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_add_a_domain()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);

        $response = $this->post(route('domains.store'), [
            'name' => 'new-domain',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('domains', [
            'name' => 'new-domain',
            'tenant_id' => $tenant->id,
        ]);
        $this->assertDatabaseHas('activity_log', [
            'subject_type' =>  'App\Domain',
        ]);
    }

    public function test_creating_domains_requires_a_name()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);

        $response = $this->post(route('domains.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('domains', [
            'name' => 'new-domain',
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_a_user_can_update_a_domain()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);
        $domain = factory(Domain::class)->create([
            'name' => 'original',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->post(route('domains.update', $domain->ulid), [
            'name' => 'changed',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('domains', [
            'id' => $domain->id,
            'name' => 'changed',
        ]);

        $activity = DB::table('activity_log')
            ->where('subject_id', $domain->id)
            ->where('subject_type', 'App\Domain')
            ->get();

        $this->assertEquals(2, $activity->count());
    }

    public function test_a_user_cannot_remove_a_domain()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);
        $domain = factory(Domain::class)->create([
            'name' => 'original',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->delete(route('domains.destroy', $domain->ulid));

        $response->assertForbidden();
        $this->assertDatabaseHas('domains', [
            'id' => $domain->id,
        ]);
    }

    public function test_an_admin_can_remove_a_domain_with_no_cards()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->states('admin')->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);
        $domain = factory(Domain::class)->create([
            'name' => 'original',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->delete(route('domains.destroy', $domain->ulid));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('domains', [
            'id' => $domain->id,
        ]);
    }

    public function test_an_admin_cannot_remove_a_domain_with_cards()
    {
        $this->withoutExceptionHandling();
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->states('admin')->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);
        $domain = factory(Domain::class)->create([
            'name' => 'original',
            'tenant_id' => $tenant->id,
        ]);
        $card = factory(Card::class)->create([
            'domain_id' => $domain->id,
            'created_by' => $user->id,
        ]);

        $response = $this->delete(route('domains.destroy', $domain->ulid));

        $response->assertSessionHas('warning');
        $this->assertDatabaseHas('domains', [
            'id' => $domain->id,
        ]);
    }
}
