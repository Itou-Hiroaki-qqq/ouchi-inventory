<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'おうちで在庫くん') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col bg-gray-100">
        {{-- ヘッダー --}}
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    {{-- アプリ名 --}}
                    <div class="flex-shrink-0 text-lg font-bold">
                        <a href="{{ route('dashboard') }}">おうちで在庫くん</a>
                    </div>

                    {{-- ハンバーガーメニュー / ナビリンク --}}
                    <div class="flex items-center">
                        <button type="button"
                            class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-200"
                            aria-controls="main-menu" aria-expanded="false"
                            onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <nav class="hidden sm:flex space-x-4">
                            <a href="{{ route('purchases.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">次回購入リスト</a>
                            <a href="{{ route('genres.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">ジャンル設定</a>

                            @php
                                $user = Auth::user();
                            @endphp
                            @if($user)
                                @if($user->sharedWith()->exists())
                                    <a href="{{ route('shares.index') }}"
                                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">共有設定</a>
                                @endif
                                @if($user->sharedBy()->exists())
                                    <a href="{{ route('shares.sharedToMe') }}"
                                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">共有設定</a>
                                @endif
                                @if(!$user->sharedWith()->exists() && !$user->sharedBy()->exists())
                                    <a href="{{ route('shares.index') }}"
                                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">共有設定</a>
                                @endif
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200">ログアウト</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- モバイルメニュー --}}
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('purchases.index') }}"
                        class="block px-3 py-2 rounded text-base font-medium text-gray-700 hover:bg-gray-200">次回購入リスト</a>
                    <a href="{{ route('genres.index') }}"
                        class="block px-3 py-2 rounded text-base font-medium text-gray-700 hover:bg-gray-200">ジャンル設定</a>

                    @if($user)
                        @if($user->sharedWith()->exists())
                            <a href="{{ route('shares.index') }}"
                                class="block px-3 py-2 rounded text-base font-medium text-gray-700 hover:bg-gray-200">共有設定</a>
                        @endif
                        @if($user->sharedBy()->exists())
                            <a href="{{ route('shares.sharedToMe') }}"
                                class="block px-3 py-2 rounded text-base font-medium text-gray-700 hover:bg-gray-200">共有設定</a>
                        @endif
                        @if(!$user->sharedWith()->exists() && !$user->sharedBy()->exists())
                            <a href="{{ route('shares.index') }}"
                                class="block px-3 py-2 rounded text-base font-medium text-gray-700 hover:bg-gray-200">共有設定</a>
                        @endif
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 rounded text-base font-medium text-gray-700 hover:bg-gray-200">ログアウト</button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- フッター --}}
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-gray-500">
                All Rights Reserved 2025 ©︎ Hiroaki Ito
            </div>
        </footer>
    </div>
</body>

</html>
