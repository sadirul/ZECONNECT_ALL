@extends('admin.layouts.app')

@section('content')
    <div>
        <h2 class="text-3xl font-semibold text-slate-800 md:text-4xl">Dashboard</h2>
        <p class="text-sm text-slate-500">Overview of key module totals</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.agents.index') }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">Agents</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['agents'] }}</p>
                </div>
                <div class="rounded-lg bg-blue-100 p-2 text-blue-700">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zM4 15a6 6 0 1112 0v1H4v-1z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['type' => 'product']) }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">Products</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['products'] }}</p>
                </div>
                <div class="rounded-lg bg-emerald-100 p-2 text-emerald-700">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M3 4.75A1.75 1.75 0 014.75 3h10.5A1.75 1.75 0 0117 4.75v10.5A1.75 1.75 0 0115.25 17H4.75A1.75 1.75 0 013 15.25V4.75z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['type' => 'service']) }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">Services</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['services'] }}</p>
                </div>
                <div class="rounded-lg bg-violet-100 p-2 text-violet-700">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.25 5A2.25 2.25 0 002 7.25v5.5A2.25 2.25 0 004.25 15h11.5A2.25 2.25 0 0018 12.75v-5.5A2.25 2.25 0 0015.75 5H4.25zm2.5 2.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 3a.75.75 0 000 1.5h4a.75.75 0 000-1.5h-4z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.banners.index') }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">Banners</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats['banners'] }}</p>
                </div>
                <div class="rounded-lg bg-rose-100 p-2 text-rose-700">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 5.75A1.75 1.75 0 014.75 4h10.5A1.75 1.75 0 0117 5.75v8.5A1.75 1.75 0 0115.25 16H4.75A1.75 1.75 0 013 14.25v-8.5zm3.47 6.78a.75.75 0 001.06 0l1.22-1.22 1.47 1.47a.75.75 0 001.06 0l2.47-2.47a.75.75 0 10-1.06-1.06l-1.94 1.94-1.47-1.47a.75.75 0 00-1.06 0l-1.75 1.75a.75.75 0 000 1.06z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </a>
    </div>
@endsection
