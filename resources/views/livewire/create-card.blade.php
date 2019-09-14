<div>
  @if ($visible)
  <form>
    <div class="field">
      <label for="title">Title</label>
      <input type="text" name="title" id="title" wire:model="title">
      @error('title')
        <p class="text-red-400">{{ $message }}</p>
      @enderror
    </div>
    <div class="field">
      <label for="body">Content</label>
      <textarea name="body" id="body" cols="30" rows="10" wire:model="body"></textarea>
      @error('body')
        <p class="text-red-400">{{ $message }}</p>
      @enderror
    </div>
    <div class="field">
      <label for="source">Source</label>
      <input type="text" name="source" id="source" wire:model="source">
      @error('source')
        <p class="text-red-400">{{ $message }}</p>
      @enderror
    </div>
  </form>
  <button wire:click="createCard">Save</button>
  <button wire:click="cancelCard">Cancel</button>
  @else
  <button wire:click="toggle">Add Card</button>
  @endif
</div>
