<div class="rolodex p-4">
  @foreach($cards as $card)
  <div class="card">
    <header class="cursor-pointer" wire:click="select('{{ $card->ulid }}')">{{ $card->title }}</header>
    <article>
      <p wire:click="select('{{ $card->ulid }}')">
        {!! $card->snippet !!}
      </p>
    </article>
    @if ($card->source)
    <footer>{{ $card->source }}</footer>
    @endif
  </div>
  @endforeach
</div>
