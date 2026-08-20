<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard Admin') - Ekskul App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-graduation-cap mr-2"></i>Ekskul App
                </h1>
                <p class="text-sm text-gray-500 mt-1">Admin Panel</p>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg @if(request()->routeIs('admin.dashboard')) bg-blue-50 text-blue-600 @else text-gray-700 hover:bg-gray-50 @endif transition">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                
                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-user-tie w-5"></i>
                    <span class="ml-3">Kelola Ekskul</span>
                </a>
                
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center px-4 py-3 rounded-lg @if(request()->routeIs('admin.ekskul.*')) bg-blue-50 text-blue-600 @else text-gray-700 hover:bg-gray-50 @endif transition">
                    <i class="fas fa-building w-5"></i>
                    <span class="ml-3">Kelola Siswa</span>
                    <span class="ml-auto text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded">Soon</span>
                </a>
                
                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-user-tie w-5"></i>
                    <span class="ml-3">Kelola Pembina</span>
                    <span class="ml-auto text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded">Soon</span>
                </a>
                
                <hr class="my-4 border-gray-200">
                
                <!-- Profile sementara diarahkan ke dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-user w-5"></i>
                    <span class="ml-3">Profile</span>
                </a>
                
                <!-- Logout sementara diarahkan ke dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
            </nav>
        </aside>
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ date('d F Y') }}
                        </span>
                        <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center">
                            <span class="text-sm font-bold">A</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>