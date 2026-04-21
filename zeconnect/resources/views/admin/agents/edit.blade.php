@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Edit Agent</h2>
        <p class="text-sm text-slate-500">Update agent account information</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.agents.update', $agent) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.agents._form', ['agent' => $agent, 'submitLabel' => 'Update Agent'])
        </form>
    </div>
@endsection
