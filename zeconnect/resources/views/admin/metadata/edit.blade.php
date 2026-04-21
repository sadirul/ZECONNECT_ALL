@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Edit Metadata</h2>
        <p class="text-sm text-slate-500">Update metadata entry</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.metadata.update', $metadata) }}">
            @method('PUT')
            @include('admin.metadata._form', ['metadata' => $metadata, 'submitLabel' => 'Update Metadata'])
        </form>
    </div>
@endsection
