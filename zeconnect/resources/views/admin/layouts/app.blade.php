<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    @php($admin = auth('admin')->user())

    <div class="min-h-screen md:flex">
        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/50 md:hidden"></div>

        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-gradient-to-b from-slate-900 to-slate-950 px-4 py-5 text-slate-200 transition-transform duration-200 md:static md:translate-x-0">
            <div class="border-b border-white/10 pb-4">
                <h1 class="text-xl font-semibold">ZeConnect Admin</h1>
            </div>

            @php($isAgentMenuOpen = request()->routeIs('admin.agents.*'))
            @php($isProductsMenuOpen = request()->routeIs('admin.products.*'))

            <nav class="mt-4 flex-1 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    Dashboard
                </a>

                <div>
                    <button
                        id="agents-menu-toggle"
                        type="button"
                        class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-medium {{ $isAgentMenuOpen ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                        aria-expanded="{{ $isAgentMenuOpen ? 'true' : 'false' }}"
                    >
                        <span>Agents</span>
                        <svg id="agents-menu-icon" class="h-4 w-4 transition-transform {{ $isAgentMenuOpen ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="agents-submenu" class="mt-1 space-y-1 pl-3 {{ $isAgentMenuOpen ? '' : 'hidden' }}">
                        <a href="{{ route('admin.agents.index') }}" class="block rounded-md px-3 py-2 text-sm {{ request()->routeIs('admin.agents.index') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            All Agent
                        </a>
                        <a href="{{ route('admin.agents.create') }}" class="block rounded-md px-3 py-2 text-sm {{ request()->routeIs('admin.agents.create') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Add Agent
                        </a>
                    </div>
                </div>

                <div>
                    <button
                        id="products-menu-toggle"
                        type="button"
                        class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-medium {{ $isProductsMenuOpen ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                        aria-expanded="{{ $isProductsMenuOpen ? 'true' : 'false' }}"
                    >
                        <span>Products</span>
                        <svg id="products-menu-icon" class="h-4 w-4 transition-transform {{ $isProductsMenuOpen ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="products-submenu" class="mt-1 space-y-1 pl-3 {{ $isProductsMenuOpen ? '' : 'hidden' }}">
                        <a href="{{ route('admin.products.index') }}" class="block rounded-md px-3 py-2 text-sm {{ request()->routeIs('admin.products.index') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            All Products
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="block rounded-md px-3 py-2 text-sm {{ request()->routeIs('admin.products.create') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            Add Product
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.banners.index') }}" class="flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.banners.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    Banner
                </a>

                <a href="{{ route('admin.metadata.index') }}" class="flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.metadata.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    Metadata
                </a>
            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" class="pt-4">
                @csrf
                <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-sm font-medium text-rose-400 hover:bg-rose-500/10">
                    Logout
                </button>
            </form>
        </aside>

        <main class="min-w-0 flex-1 overflow-x-hidden">
            <header class="border-b border-slate-200 bg-white px-4 py-3 md:px-6">
                <div class="flex items-center justify-end">
                    <button id="sidebar-toggle" type="button" class="mr-auto inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-slate-600 hover:bg-slate-100 md:hidden" aria-label="Open sidebar">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 5a.75.75 0 01.75-.75h12.5a.75.75 0 010 1.5H3.75A.75.75 0 013 5zm0 5a.75.75 0 01.75-.75h12.5a.75.75 0 010 1.5H3.75A.75.75 0 013 10zm0 5a.75.75 0 01.75-.75h12.5a.75.75 0 010 1.5H3.75A.75.75 0 013 15z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="relative" id="profile-menu-wrapper">
                        <button type="button" id="profile-menu-button" class="flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-slate-100">
                            @if ($admin->profile_pic)
                                <img src="{{ asset('storage/'.$admin->profile_pic) }}" alt="Profile picture" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-xs font-semibold text-white">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="hidden text-sm font-medium sm:block">{{ $admin->name }}</span>
                            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="profile-menu-dropdown" class="absolute right-0 z-20 mt-2 hidden w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                            <a href="{{ route('admin.profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">Profile</a>
                            <a href="{{ route('admin.settings.password.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">Settings</a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="mt-1 block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-rose-500 transition hover:bg-rose-500/10">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <section class="space-y-4 px-4 py-4 md:px-6">
                @if (session('success'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const agentsMenuToggle = document.getElementById('agents-menu-toggle');
        const agentsSubmenu = document.getElementById('agents-submenu');
        const agentsMenuIcon = document.getElementById('agents-menu-icon');
        const productsMenuToggle = document.getElementById('products-menu-toggle');
        const productsSubmenu = document.getElementById('products-submenu');
        const productsMenuIcon = document.getElementById('products-menu-icon');
        const profileButton = document.getElementById('profile-menu-button');
        const profileDropdown = document.getElementById('profile-menu-dropdown');
        const profileWrapper = document.getElementById('profile-menu-wrapper');

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        };

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', openSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        if (agentsMenuToggle && agentsSubmenu && agentsMenuIcon) {
            agentsMenuToggle.addEventListener('click', () => {
                const isHidden = agentsSubmenu.classList.contains('hidden');
                agentsSubmenu.classList.toggle('hidden');
                agentsMenuIcon.classList.toggle('rotate-180');
                agentsMenuToggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });
        }

        if (productsMenuToggle && productsSubmenu && productsMenuIcon) {
            productsMenuToggle.addEventListener('click', () => {
                const isHidden = productsSubmenu.classList.contains('hidden');
                productsSubmenu.classList.toggle('hidden');
                productsMenuIcon.classList.toggle('rotate-180');
                productsMenuToggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });
        }

        profileButton.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!profileWrapper.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: form.dataset.confirm || 'Please confirm this action.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
