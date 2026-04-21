@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Products</h2>
            <p class="text-sm text-slate-500">Manage products and services</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Add Product
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by title"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
            >

            <select name="type" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                <option value="">All Types</option>
                <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Product</option>
                <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Service</option>
            </select>

            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">Filter</button>
                <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Image</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Price</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Created Date</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                @if ($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/'.$product->images->first()->image_path) }}" alt="Thumbnail" class="h-10 w-14 rounded object-cover">
                                @else
                                    <div class="flex h-10 w-14 items-center justify-center rounded bg-slate-100 text-xs text-slate-500">No Img</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-800">{{ $product->title }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ number_format((float) $product->price, 2) }}</td>
                            <td class="px-4 py-3 text-slate-700 capitalize">{{ $product->type }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $product->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-100">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Delete this product?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-rose-200 px-3 py-1.5 text-xs text-rose-600 hover:bg-rose-50">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $products->links() }}
    </div>
@endsection
