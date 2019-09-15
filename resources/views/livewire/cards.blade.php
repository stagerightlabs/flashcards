<div class="rolodex flex flex-wrap mx-auto px-4">
  @foreach($cards as $card)
  <div class="w-full md:w-1/3">
    <div class="card font-serif bg-gray-100 text-gray-800 text-xl shadow m-2">
      <header class="text-2xl p-1 px-2">{{ $card->title }}</header>
      <div class="p-2">
        <p>{{ $card->body }}</p>
        <footer>{{ $card->source }}</footer>
      </div>
    </div>
  </div>
  @endforeach
</div>
