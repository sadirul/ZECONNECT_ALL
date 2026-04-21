@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Add Agent</h2>
        <p class="text-sm text-slate-500">Create a new agent account</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.agents.store') }}" enctype="multipart/form-data">
            @include('admin.agents._form', ['submitLabel' => 'Create Agent'])
        </form>
    </div>
@endsection
