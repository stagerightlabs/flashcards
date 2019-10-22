<select
  id="domain-selector"
  class="rounded px-2 py-1 h-12 text-lg text-gray-600 shadow\q"
  wire:change="$emit('selectDomain')"
  wire:model="selectedDomainUlid"
>
  @foreach ($domains as $domain)
  <option value="{{ $domain->ulid }}">
    {{ $domain->name }}</option>
  @endforeach
  <option disabled>──────────</option>
  <option value="create">Create New Domain</option>
</select>

@push('scripts')
<script type="text/javascript">
document.addEventListener('livewire:available', function () {
  window.livewire.on('selectDomain',() => {

    var param = document.getElementById("domain-selector").value;
    var form = document.createElement('form');

    var token_input = document.createElement("input");
    token_input.name = '_token';
    token_input.value = '{{ csrf_token() }}';
    token_input.type = 'hidden';
    form.appendChild(token_input);

    var domain_input = document.createElement("input");
    domain_input.name = 'domain';
    domain_input.value = param;
    domain_input.type = 'hidden';
    form.appendChild(domain_input);

    form.action = '{{ route('domains.switch') }}';
    form.method = 'POST';
    document.body.appendChild(form);
    form.submit();
    });
});
</script>
@endpush
