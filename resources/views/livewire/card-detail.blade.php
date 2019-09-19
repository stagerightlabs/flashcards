<div>
  @if ($card)
  <div class="fixed inset-0 w-full h-screen flex items-center justify-center bg-smoke z-10">
    <div class="card w-full md:w-5/6 z-20 mx-4">
      <header class="flex justify-between">
        <span>{{ $card->title }}</span>
        <span class="pr-3">
          <button wire:click="closeCard">@svg('close', 'w-4')</button>
        </span>
      </header>
      <article>
        {{ $card->body }}
      </article>
      @if ($card->source)
      <footer>{{ $card->source }}</footer>
      @endif
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script type="text/javascript">
var escapeHandler = function(e) {
  if (e.key === 'Escape') {
    livewire.emit('requestCardClosure')
  }
};

document.addEventListener("livewire:load", function(event) {
  if (ulid = @this.get('ulid')) {
    document.addEventListener('keydown', escapeHandler);
  }
})

document.addEventListener('livewire:available', function () {
  window.livewire.on('openCard', function(ulid) {
    if (ulid) {
      var pageUrl = '?card=' + ulid;
      window.history.pushState('', '', pageUrl);
    }
    document.addEventListener('keydown', escapeHandler);
  });

  window.livewire.on('closeCard', function(ulid) {
    if (ulid) {
      window.history.pushState('', '', '/');
      document.removeEventListener('keydown', escapeHandler);
    }
  });
});
</script>
@endpush
