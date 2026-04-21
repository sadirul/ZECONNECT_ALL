<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-700/25 via-fuchsia-600/20 to-cyan-500/20"></div>
        <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl"></div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-6 py-10">
            <div class="grid w-full gap-10 lg:grid-cols-2">
                <div class="hidden lg:flex lg:flex-col lg:justify-center">
                    <p class="mb-4 inline-flex w-fit items-center rounded-full border border-white/15 px-3 py-1 text-xs font-medium uppercase tracking-wider text-indigo-200">
                        Zeconnect Admin
                    </p>
                    <h1 class="text-4xl font-bold leading-tight">
                        Welcome back to your
                        <span class="bg-gradient-to-r from-cyan-300 to-indigo-300 bg-clip-text text-transparent">modern admin panel</span>
                    </h1>
                    <p class="mt-5 max-w-lg text-slate-300">
                        Manage your shop, customers, and operations from one secure dashboard.
                    </p>
                </div>

                <div class="mx-auto w-full max-w-md rounded-2xl border border-white/10 bg-white/10 p-8 shadow-2xl backdrop-blur-xl">
                    <h2 class="text-2xl font-semibold">Admin Sign In</h2>
                    <p class="mt-2 text-sm text-slate-300">Enter your credentials to access the dashboard.</p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-lg border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-slate-200">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-xl border border-white/15 bg-slate-950/40 px-4 py-3 text-slate-100 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none"
                                placeholder="admin@zeconnect.test"
                            >
                        </div>

                        <div>
                            <label for="password" class="mb-1 block text-sm font-medium text-slate-200">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="w-full rounded-xl border border-white/15 bg-slate-950/40 px-4 py-3 text-slate-100 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none"
                                placeholder="********"
                            >
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-300">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-indigo-400 focus:ring-indigo-400">
                            Remember me
                        </label>

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-4 py-3 font-semibold text-white shadow-lg transition hover:scale-[1.01] hover:from-indigo-400 hover:to-cyan-400"
                        >
                            Sign In
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
