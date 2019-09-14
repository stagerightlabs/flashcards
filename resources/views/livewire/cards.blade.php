<div>
  @foreach($cards as $card)
  <div class="card">
    <header>{{ $card->title }}</header>
    <p>{{ $card->body }}</p>
    <footer>{{ $card->source }}</footer>
  </div>
  @endforeach
</div>
