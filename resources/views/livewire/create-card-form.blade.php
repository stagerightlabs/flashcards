<div>
  @if ($visible)
  <div class="fixed inset-0 w-full h-screen flex items-center justify-center">
    <div class="w-full max-w-3xl bg-gray-200 shadow-xl md:rounded-lg p-4 md:p-8">
      <form class="">
        <div class="field">
          <label for="title" class="text-gray-600 text-lg">Title</label>
          <input type="text" name="title" id="title" wire:model="title" class="w-full h-12 text-2xl mb-4 bg-gray-100 font-serif p-1 text-gray-800">
          @error('title')
            <p class="text-red-400">{{ $message }}</p>
          @enderror
        </div>
        <div class="field">
          <label for="body" class="text-gray-600 text-lg">Content</label>
          <textarea name="body" id="body" cols="30" rows="10" wire:model="body" class="w-full mb-4 bg-gray-100 font-serif p-1 text-lg text-gray-800"></textarea>
          @error('body')
            <p class="text-red-400">{{ $message }}</p>
          @enderror
        </div>
        <div class="field">
          <label for="source" class="text-gray-600 text-lg">Source</label>
          <input type="text" name="source" id="source" wire:model="source" class="w-full mb-4 h-12 bg-gray-100 font-serif p-1 text-lg text-gray-800">
          @error('source')
            <p class="text-red-400">{{ $message }}</p>
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
