@section('title', 'Generate A Venue')

<div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <label class="block">
      <span class="text-gray-700">Rows</span>
      <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" wire:model.lazy="rows">
      @error('rows') <span class="text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="block">
      <span class="text-gray-700">Columns</span>
      <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" wire:model.lazy="columns">
      @error('columns') <span class="text-red-500">{{ $message }}</span> @enderror
    </label>
    <div class="md:col-span-2 flex justify-center">
      <button class="rounded bg-cyan-500 hover:bg-cyan-600 text-white p-4" wire:click="generate">Generate Venue</button>
    </div>
    <div class="md:col-span-2">
      <label class="block">
        <span class="text-gray-700">Venue JSON:</span>
        <textarea name="venue" wire:model="json" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 bg-gray-100" rows="15" readonly></textarea>
      </label>
    </div>
  </div>
</div>
