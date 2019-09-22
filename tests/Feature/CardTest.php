<?php

namespace Tests\Feature;

use App\Card;
use App\User;
use App\Domain;
use App\Tenant;
use Tests\TestCase;
use Livewire\Livewire;
use App\Searchable\SearchIndex;
use App\Http\Livewire\CardDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\CreateCardForm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_add_a_new_card()
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

        Livewire::test(CreateCardForm::class)
            ->set('title', 'Photosynthesis')
            ->set('body', 'A chemical process whereby plants convert sunlight into energy.')
            ->set('source', 'http://example.com')
            ->call('createCard');

        $this->assertDatabaseHas('cards', [
            'title' => 'Photosynthesis',
            'body' => 'A chemical process whereby plants convert sunlight into energy.',
            'source' => 'http://example.com',
            'domain_id' => $domain->id,
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

    public function test_a_user_cannot_add_malicious_content_to_a_card()
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

        Livewire::test(CreateCardForm::class)
            ->set('title', 'Photosynthesis<script>alert();</script>')
            ->set('body', 'A chemical process whereby <script>alert();</script>plants convert sunlight into energy.')
            ->set('source', 'http://example.com<script>alert();</script>')
            ->call('createCard');

        $this->assertDatabaseHas('cards', [
            'title' => 'Photosynthesis',
            'body' => 'A chemical process whereby plants convert sunlight into energy.',
            'source' => 'http://example.com',
            'domain_id' => $domain->id,
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
        $this->withoutExceptionHandling();
        $tenant = factory(Tenant::class)->create();
        $domain = factory(Domain::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $this->actingAs($user);
        $card = factory(Card::class)->create([
            'created_by' => $user->id,
            'domain_id' => $domain->id,
        ]);

        Livewire::test(CardDetail::class)
            ->call('selectCardByUlid', $card->ulid)
            ->set('editTitle', 'Changed')
            ->set('editBody', 'This has been changed')
            ->set('editSource', 'New Source')
            ->call('updateCard');

        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'title' => 'Changed',
            'body' => 'This has been changed',
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
        $this->withoutExceptionHandling();
        $tenant = factory(Tenant::class)->create();
        $domain = factory(Domain::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $this->actingAs($user);
        $card = factory(Card::class)->create([
            'created_by' => $user->id,
            'domain_id' => $domain->id,
        ]);

        Livewire::test(CardDetail::class)
            ->call('selectCardByUlid', $card->ulid)
            ->call('deleteCard');

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
        $domain = factory(Domain::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $userA = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $userB = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $this->actingAs($userB);

        $card = factory(Card::class)->create([
            'created_by' => $userA->id,
            'domain_id' => $domain->id,
        ]);

        Livewire::test(CardDetail::class)
            ->call('selectCardByUlid', $card->ulid)
            ->call('deleteCard')
            ->assertSee(CardDetail::DEFAULT_ERROR_MESSAGE);

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
        $domain = factory(Domain::class)->create([
            'tenant_id' => $tenant->id,
        ]);
        $user = factory(User::class)->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $admin = factory(User::class)->state('admin')->create([
            'tenant_id' => $tenant->id,
            'current_domain_id' => $domain->id,
        ]);
        $this->actingAs($admin);

        $card = factory(Card::class)->create([
            'created_by' => $user->id,
            'domain_id' => $domain->id,
        ]);

        Livewire::test(CardDetail::class)
            ->call('selectCardByUlid', $card->ulid)
            ->call('deleteCard');

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
}
