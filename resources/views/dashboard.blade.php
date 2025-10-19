@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        おうちの在庫くん
    </h2>

    {{-- ジャンルタブ群 --}}
    @if(isset($genres) && $genres->isNotEmpty())
    <div x-data="{ activeTab: '{{ $genres->first()->id }}' }">
        {{-- タブボタン --}}
        <div class="flex overflow-x-auto border-b mb-6 space-x-4">
            @foreach($genres as $genre)
            <button
                @click="activeTab = '{{ $genre->id }}'"
                :class="activeTab === '{{ $genre->id }}' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                class="px-3 py-2 text-sm font-medium whitespace-nowrap">
                {{ $genre->name }}
                @if (!empty($genre->isShared) && $genre->isShared)
                <span class="ml-1 text-xs text-gray-500">（共有）</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- タブの中身 --}}
        @foreach($genres as $genre)
        <div x-show="activeTab === '{{ $genre->id }}'" class="space-y-4">
            {{-- アイテム登録フォーム --}}
            <form method="POST" action="{{ route('items.store', $genre->id) }}" class="flex space-x-2 mb-2">
                @csrf
                <input
                    type="text"
                    name="name"
                    placeholder="アイテム名"
                    class="border px-2 py-1 w-full rounded"
                    required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
                    登録
                </button>
            </form>

            {{-- アイテム一覧 --}}
            <ul class="space-y-2">
                @foreach($genre->items()->orderByDesc('total_added_count')->get() as $item)
                <li class="@if($item->quantity === 0) bg-gray-100 text-gray-400 @else bg-white @endif border p-3 rounded flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        {{-- 次回購入ボタン --}}
                        <form method="POST" action="{{ route('purchases.store', [$genre->id, $item->id]) }}">
                            @csrf
                            <button type="submit" class="bg-orange-500 text-white px-2 py-1 rounded hover:bg-orange-600 text-xs">
                                次回購入
                            </button>
                        </form>

                        {{-- 商品名リンク --}}
                        <a href="{{ route('items.edit', [$genre->id, $item->id]) }}"
                            class="font-semibold hover:underline">
                            {{ $item->name }}
                        </a>
                    </div>

                    <div class="flex items-center space-x-2">
                        {{-- 減らすボタン --}}
                        <form method="POST" action="{{ route('items.decrement', [$genre->id, $item->id]) }}">
                            @csrf
                            @method('PATCH')
                            <button class="bg-gray-300 px-2 py-1 rounded hover:bg-gray-400">−</button>
                        </form>

                        {{-- 数量 --}}
                        <span class="w-8 text-center @if($item->quantity === 0) text-gray-400 @endif">{{ $item->quantity }}</span>

                        {{-- 増やすボタン --}}
                        <form method="POST" action="{{ route('items.increment', [$genre->id, $item->id]) }}">
                            @csrf
                            @method('PATCH')
                            <button class="bg-green-300 px-2 py-1 rounded hover:bg-green-400">＋</button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
    @else
    <p>ジャンルが登録されていません。まずはジャンルを追加してください。</p>
    @endif
</div>
@endsection
