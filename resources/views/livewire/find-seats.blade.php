@section('title', 'Find Best Available Seats')

<div>
  <div class="grid grid-cols-1 gap-4">
    <p class="text-cyan-700">Paste a JSON payload below and this tool will attempt to find the best available seats. It must be identical to the format specified in the documentation.</p>
    <label class="block">
      <span class="text-gray-700">Payload:</span>
      <textarea name="payload" wire:model.lazy="payload" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" rows="10"></textarea>
      @error('payload') <span class="text-red-500">{{ $message }}</span> @enderror
    </label>
    <div class="flex justify-center">
      <button class="rounded bg-cyan-500 hover:bg-cyan-600 text-white p-4" wire:click="find">Find Seats</button>
    </div>
    <label class="block">
      <span class="text-gray-700">Result:</span>
      <textarea name="venue" wire:model="determination" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 bg-gray-100" rows="5" readonly></textarea>
    </label>
  </div>
</div>
