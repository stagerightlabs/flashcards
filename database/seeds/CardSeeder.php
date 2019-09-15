<?php

use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = App\Tenant::first() ?? factory(App\Tenant::class)->create();

        $domain = App\Domain::first() ?? factory(App\Domain::class)->create([
            'tenant_id' => $tenant->id,
        ]);

        $user = $tenant->users->first() ?? factory(App\User::class)->create([
            'tenant_id' => $tenant->id,
        ]);

        factory(App\Card::class, 40)->create([
            'created_by' => $user->id,
            'domain_id' => $domain->id,
        ]);
    }
}
