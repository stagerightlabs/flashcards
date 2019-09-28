<div>
  @if ($card)
  <div class="fixed inset-0 w-full h-screen flex items-center justify-center bg-smoke z-10">
    @if ($mode == 'read')
    <div class="card w-full md:w-5/6 z-20">
      <header class="flex justify-between">
        <span>
          {{ $card->title }}
          <button class="text-gray-500" wire:click="enterEditMode">@svg('edit-pencil', 'w-4')</button>
          <button class="text-gray-500" wire:click="deleteCard">@svg('trash', 'w-4')</button>
        </span>
        <span class="pr-3 text-gray-600">
          <button wire:click="closeCard">@svg('close', 'w-4')</button>
        </span>
      </header>
      <article class="overflow-y-scroll h-full p-8">
        @if ($errorMessage)
          <p class="w-full p-4 bg-gray-600 text-gray-100 rounded mb-4">{{ $errorMessage }}</p>
        @endif
        {!! $card->html !!}
      </article>
      @if ($card->source)
      <footer>{{ $card->source }}</footer>
      @endif
    </div>
    @elseif ($mode == 'edit')
    <div class="w-full md:w-5/6 bg-gray-100 shadow-xl rounded-sm p-4 md:p-8 z-20">
      @if ($errorMessage)
        <p class="w-full p-4 bg-gray-600 text-gray-100 rounded mb-4">{{ $errorMessage }}</p>
      @endif
      <form id="wrapper">
        <div class="field mb-4">
          <label for="title" class="text-gray-600 text-lg">Title</label>
          <input type="text" name="title" id="title" wire:model="editTitle" class="w-full h-12 text-2xl bg-gray-100 font-serif p-2 text-gray-800 border">
          @error('title')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
        <div class="field mb-4">
          <label for="body" class="text-gray-600 text-lg">Content</label>
          <textarea name="body" id="body" cols="30" rows="10" wire:model="editBody" class="w-full bg-gray-100 font-serif p-2 text-lg text-gray-800 border"></textarea>
          @error('body')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
        <div class="field mb-4">
          <label for="source" class="text-gray-600 text-lg">Source</label>
          <input type="text" name="source" id="source" wire:model="editSource" class="w-full h-12 bg-gray-100 font-serif p-2 text-lg text-gray-800 border">
          @error('source')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
        <div class="field mb-4">
          <label for="pageNumber" class="text-gray-600 text-lg">Page Number:</label>
          <input type="text" name="pageNumber" id="pageNumber" wire:model="editPageNumber" class="w-full h-12 bg-gray-100 font-serif p-2 text-lg text-gray-800 border">
          @error('pageNumber')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
      </form>
      <footer class="flex justify-between">
        <button wire:click="updateCard" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">Save</button>
        <button wire:click="enterReadMode" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
      </footer>
    </div>
    @endif
  </div>
  @endif
</div>

@push('scripts')
<script type="text/javascript">
var escapeHandler = function(e) {
  var mode = @this.get('mode');
  if (e.key === 'Escape' && mode === 'read') {
    livewire.emit('requestCardClosure')
  } else if (e.key === 'Escape' && mode === 'edit') {
    livewire.emit('requestReadMode');
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
