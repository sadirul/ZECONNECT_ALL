@csrf
@php($product = $product ?? null)

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $product->title ?? '') }}"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >
        @error('title')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Type</label>
        <select
            name="type"
            id="product-type"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >
            @php($selectedType = old('type', $product->type ?? 'product'))
            <option value="product" {{ $selectedType === 'product' ? 'selected' : '' }}>Product</option>
            <option value="service" {{ $selectedType === 'service' ? 'selected' : '' }}>Service</option>
        </select>
        @error('type')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-slate-500">Stock is not required and stays 0 by default.</p>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Price</label>
        <input
            type="number"
            step="0.01"
            min="0"
            name="price"
            value="{{ old('price', $product->price ?? 0) }}"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >
        @error('price')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
        <textarea
            name="description"
            rows="4"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Images (multiple)</label>
        <input
            type="file"
            name="images[]"
            accept="image/*"
            multiple
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >
        @error('images')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @error('images.*')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if ($product && $product->images->isNotEmpty())
        <div class="md:col-span-2">
            <p class="mb-2 text-sm font-medium text-slate-700">Existing Images (mark to remove)</p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($product->images as $image)
                    <label class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="Product image" class="h-24 w-full object-cover">
                        <span class="flex items-center gap-2 px-2 py-2 text-xs text-slate-600">
                            <input type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            Remove
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            >
            Active
        </label>
    </div>
</div>

<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        {{ $submitLabel ?? 'Save Product' }}
    </button>
    <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
        Cancel
    </a>
</div>
