<div class="rolodex p-4">
  @foreach($cards as $card)
  <div class="card">
    <div class="font-serif bg-gray-100 text-gray-800 text-xl shadow">
      <header class="text-3xl p-1 px-2">{{ $card->title }}</header>
      <div class="px-2 p-1">
        <p>{{ $card->body }}</p>
      </div>
      @if ($card->source)
      <footer class="text-base p-2 text-gray-700 bg-gray-200">{{ $card->source }}</footer>
      @endif
    </div>
  </div>
  @endforeach
</div>
