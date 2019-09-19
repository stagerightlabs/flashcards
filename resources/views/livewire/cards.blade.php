<div class="rolodex p-4">
  @foreach($cards as $card)
  <div class="card">
    <header class="cursor-pointer" wire:click="select('{{ $card->ulid }}')">{{ $card->title }}</header>
    <article>
      <p wire:click="select('{{ $card->ulid }}')">
        {!! $card->snippet !!}{{ $card->is_longer_than_snippet ? '...' : '' }}
        @svg('cheveron-outline-right', 'w-5 inline text-gray-500 cursor-pointer')
      </p>
    </article>
    @if ($card->source)
    <footer>{{ $card->source }}</footer>
    @endif
  </div>
  @endforeach
</div>
