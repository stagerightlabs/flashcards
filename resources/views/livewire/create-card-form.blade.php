<div>
  @if ($visible)
  <div class="fixed inset-0 w-full h-screen flex items-center justify-center bg-smoke z-10">
    <div class="w-full md:w-5/6 bg-gray-100 shadow-xl rounded-sm p-4 md:p-8 z-20">
      <form class="">
        <div class="field mb-4">
          <label for="title" class="text-gray-600 text-lg">Title</label>
          <input type="text" name="title" id="title" wire:model="title" class="w-full h-12 text-2xl bg-gray-100 font-serif p-2 text-gray-800 border">
          @error('title')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
        <div class="field mb-4">
          <label for="body" class="text-gray-600 text-lg">Content</label>
          <textarea name="body" id="body" cols="30" rows="10" wire:model="body" class="w-full bg-gray-100 font-serif p-2 text-lg text-gray-800 border"></textarea>
          @error('body')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
        <div class="field mb-4">
          <label for="source" class="text-gray-600 text-lg">Source</label>
          <input type="text" name="source" id="source" wire:model="source" class="w-full h-12 bg-gray-100 font-serif p-2 text-lg text-gray-800 border">
          @error('source')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
        <div class="field mb-4">
          <label for="pageNumber" class="text-gray-600 text-lg">Page Number:</label>
          <input type="text" name="pageNumber" id="pageNumber" wire:model="pageNumber" class="w-full h-12 bg-gray-100 font-serif p-2 text-lg text-gray-800 border">
          @error('pageNumber')
            <p class="text-gray-800">{{ $message }}</p>
          @enderror
        </div>
      </form>
      <footer class="flex justify-between">
        <button wire:click="createCard" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">Save</button>
        <button wire:click="closeCardModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
      </footer>
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script type="text/javascript">
// This isn't working yet...
var escapeHandler = function(e) {
  if (e.key === 'Escape') {
    livewire.emit('requestModalClosure')
  }
};

window.livewire.on('requestCreateCardModal', function() {
  document.addEventListener('keydown', escapeHandler);
});

window.livewire.on('requestModalClosure', function(ulid) {
  document.removeEventListener('keydown', escapeHandler);
});
</script>
@endpush
