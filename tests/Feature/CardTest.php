<?php

namespace Tests\Feature;

use App\Card;
use App\User;
use App\Tenant;
use Tests\TestCase;
use App\Searchable\SearchIndex;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_add_a_new_card()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);

        $response = $this->post(route('cards.store'), [
            'title' => 'Photosynthesis',
            'body' => 'A chemical process whereby plants convert sunlight into energy.',
            'source' => 'http://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('cards', [
            'title' => 'Photosynthesis',
            'body' => 'A chemical process whereby plants convert sunlight into energy.',
            'source' => 'http://example.com',
            'tenant_id' => $tenant->id,
            'created_by' => $user->id,
        ]);
        $this->assertDatabaseHas('activity_log', [
            'subject_type' =>  'App\Card',
        ]);

        $card = Card::first();
        $searchIndex = SearchIndex::first();
        $this->assertTrue($searchIndex->subject->is($card));
        $this->assertNotNull($searchIndex->vector);
    }

    public function test_a_user_can_update_a_card()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);
        $card = factory(Card::class)->create([
            'created_by' => $user->id,
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->post(route('cards.update', $card->ulid), [
            'title' => 'Changed',
            'body' => 'This has been changed.',
            'source' => 'New Source',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'title' => 'Changed',
            'body' => 'This has been changed.',
            'source' => 'New Source',
        ]);

        $activity = DB::table('activity_log')
            ->where('subject_id', $card->id)
            ->where('subject_type', 'App\Card')
            ->get();

        $this->assertEquals(2, $activity->count());
        $searchIndex = SearchIndex::first();
        $this->assertTrue($searchIndex->subject->is($card));
        $this->assertNotNull($searchIndex->vector);
        $this->assertEquals(1, SearchIndex::count());
    }

    public function test_a_user_can_remove_their_own_cards()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($user);
        $card = factory(Card::class)->create([
            'created_by' => $user->id,
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->delete(route('cards.destroy', $card->ulid));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cards', [
            'ulid' => $card->ulid,
        ]);
        $this->assertDatabaseMissing('activity_log', [
            'subject_id' => $card->id,
            'subject_type' => 'App\Card',
        ]);
        $this->assertDatabaseMissing('search_indices', [
            'searchable_id' => $card->id,
            'searchable_type' => 'App\Card',
        ]);
    }

    public function test_a_user_cannot_remove_another_users_card()
    {
        $tenant = factory(Tenant::class)->create();
        $userA = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $userB = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($userB);
        $card = factory(Card::class)->create([
            'created_by' => $userA->id,
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->delete(route('cards.destroy', $card->ulid));

        $response->assertForbidden();
        $this->assertDatabaseHas('cards', [
            'ulid' => $card->ulid,
        ]);
        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $card->id,
            'subject_type' => 'App\Card',
        ]);
        $this->assertDatabaseHas('search_indices', [
            'searchable_id' => $card->id,
            'searchable_type' => 'App\Card',
        ]);
    }

    public function test_an_admin_can_remove_a_card()
    {
        $tenant = factory(Tenant::class)->create();
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $admin = factory(User::class)->state('admin')->create([
            'tenant_id' => $tenant->id,
        ]);
        $this->actingAs($admin);
        $card = factory(Card::class)->create([
            'created_by' => $user->id,
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->delete(route('cards.destroy', $card->ulid));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cards', [
            'ulid' => $card->ulid,
        ]);
        $this->assertDatabaseMissing('activity_log', [
            'subject_id' => $card->id,
            'subject_type' => 'App\Card',
        ]);
        $this->assertDatabaseMissing('search_indices', [
            'searchable_id' => $card->id,
            'searchable_type' => 'App\Card',
        ]);
    }

    public function test_guests_cannot_add_cards()
    {
        $response = $this->post(route('cards.store'), [
            'title' => 'Photosynthesis',
            'body' => 'A chemical process whereby plants convert sunlight into energy.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('cards', [
            'title' => 'Photosynthesis',
            'body' => 'A chemical process whereby plants convert sunlight into energy.',
        ]);
        $this->assertEquals(0, SearchIndex::count());
    }
}
