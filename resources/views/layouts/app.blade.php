<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Store Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <nav class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-blue-600">CSMS</h1>
                <p class="text-xs text-gray-500">Campus Store Management</p>
            </div>

            <ul class="mt-8 space-y-2 px-4">
                <li>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg @if(Route::currentRouteName() === 'dashboard') bg-blue-100 text-blue-600 @else text-gray-700 hover:bg-gray-100 @endif">
                        📊 Dashboard
                    </a>
                </li>

                @if (auth()->user()->can('viewAny', App\Models\StationaryRequest::class))
                    <li>
                        <a href="{{ route('stationary-requests.index') }}" class="block px-4 py-2 rounded-lg @if(Route::currentRouteName() === 'stationary-requests.index') bg-blue-100 text-blue-600 @else text-gray-700 hover:bg-gray-100 @endif">
                            📋 Requests
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('viewAny', App\Models\Order::class))
                    <li>
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 rounded-lg @if(Route::currentRouteName() === 'orders.index') bg-blue-100 text-blue-600 @else text-gray-700 hover:bg-gray-100 @endif">
                            📦 Orders
                        </a>
                    </li>
                @endif

                @if (auth()->user()->isHOD() || auth()->user()->isPrincipal() || auth()->user()->isTrustHead() || auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('approvals.pending') }}" class="block px-4 py-2 rounded-lg @if(Route::currentRouteName() === 'approvals.pending') bg-blue-100 text-blue-600 @else text-gray-700 hover:bg-gray-100 @endif">
                            ✔️ Approvals
                        </a>
                    </li>
                @endif

                @if (auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-lg @if(Route::currentRouteName() === 'users.index') bg-blue-100 text-blue-600 @else text-gray-700 hover:bg-gray-100 @endif">
                            👥 Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.control-panel') }}" class="block px-4 py-2 rounded-lg @if(Route::currentRouteName() === 'admin.control-panel') bg-blue-100 text-blue-600 @else text-gray-700 hover:bg-gray-100 @endif">
                            ⚙️ Admin Panel
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mt-12 px-4 border-t pt-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <a href="{{ route('users.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="bg-white border-b shadow-sm">
                <div class="max-w-7xl mx-auto px-4 py-4">
                    <h2 class="text-lg font-medium text-gray-900">@yield('page_title', 'Page')</h2>
                </div>
            </div>

            <div class="min-h-screen bg-gray-50">
                @yield('content')
            </div>
        </main>
    </div>

    @if (session('success'))
        <script>
            console.log('Success: ' + "{{ session('success') }}");
        </script>
    @endif
</body>
</html>
