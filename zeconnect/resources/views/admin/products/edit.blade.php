@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Edit Product</h2>
        <p class="text-sm text-slate-500">Update product or service details</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.products._form', ['product' => $product, 'submitLabel' => 'Update Product'])
        </form>
    </div>
@endsection
