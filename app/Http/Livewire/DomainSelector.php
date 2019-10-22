<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DomainSelector extends Component
{
    protected $domains;

    public $selectedDomainUlid;

    public function render()
    {
        return view('livewire.domain-selector')
            ->with('domains', $this->domains);
    }

    public function mount()
    {
        $this->domains = auth()->user()->tenant->domains()->orderBy('name')->get();
        $this->selectedDomainUlid = auth()->user()->domain->ulid;
    }
}
