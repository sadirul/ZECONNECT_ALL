<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Metadata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetadataController extends Controller
{
    public function index(): View
    {
        $metadatas = Metadata::query()->latest()->paginate(10);

        return view('admin.metadata.index', compact('metadatas'));
    }

    public function create(): View
    {
        $inputTypes = Metadata::INPUT_TYPES;

        return view('admin.metadata.create', compact('inputTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:metadata,key'],
            'value' => ['nullable', 'string'],
            'input_type' => ['required', 'in:'.implode(',', array_keys(Metadata::INPUT_TYPES))],
        ]);

        Metadata::create($validated);

        return redirect()
            ->route('admin.metadata.index')
            ->with('success', 'Metadata added successfully.');
    }

    public function edit(Metadata $metadata): View
    {
        $inputTypes = Metadata::INPUT_TYPES;

        return view('admin.metadata.edit', compact('metadata', 'inputTypes'));
    }

    public function update(Request $request, Metadata $metadata): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:metadata,key,'.$metadata->id],
            'value' => ['nullable', 'string'],
            'input_type' => ['required', 'in:'.implode(',', array_keys(Metadata::INPUT_TYPES))],
        ]);

        $metadata->update($validated);

        return redirect()
            ->route('admin.metadata.index')
            ->with('success', 'Metadata updated successfully.');
    }

    public function destroy(Metadata $metadata): RedirectResponse
    {
        $metadata->delete();

        return redirect()
            ->route('admin.metadata.index')
            ->with('success', 'Metadata deleted successfully.');
    }
}
