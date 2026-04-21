@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Metadata</h2>
            <p class="text-sm text-slate-500">Manage metadata key-value settings</p>
        </div>

        <a href="{{ route('admin.metadata.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Add Metadata
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Key</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Value</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Input Type</th>
                        <th class="px-4 py-3 text-left font-medium text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($metadatas as $metadata)
                        <tr>
                            <td class="px-4 py-3 text-slate-800">{{ $metadata->key }}</td>
                            <td class="max-w-md px-4 py-3 text-slate-700">
                                <span class="line-clamp-2 break-words">{{ $metadata->value }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $metadata->input_type }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.metadata.edit', $metadata) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-100">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.metadata.destroy', $metadata) }}" data-confirm="Delete this metadata?">
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
                            <td colspan="4" class="px-4 py-10 text-center text-slate-500">No metadata found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $metadatas->links() }}
    </div>
@endsection
