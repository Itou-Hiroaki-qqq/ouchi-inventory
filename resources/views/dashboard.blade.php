@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        在庫リスト
    </h2>

    {{-- JavaScript アラート表示（数量変更・増減時は除外） --}}
    @php
        $alertRoutes = ['purchases.store'];
    @endphp

    @if(session('success') && in_array(session('from_route'), $alertRoutes))
        <script>
            alert(@json(session('success')));
        </script>
    @endif

    {{-- ジャンルタブ群 --}}
    @if(isset($genres) && $genres->isNotEmpty())
    <div x-data="{ activeTab: localStorage.getItem('activeTab') || '{{ $genres->first()->id }}' }" x-init="$watch('activeTab', val => localStorage.setItem('activeTab', val))">
        {{-- タブボタン --}}
        <div class="flex overflow-x-auto border-b mb-6 space-x-4">
            @foreach($genres as $genre)
            <button
                @click="activeTab = '{{ $genre->id }}'"
                :class="activeTab === '{{ $genre->id }}' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                class="px-3 py-2 text-sm font-medium whitespace-nowrap">
                {{ $genre->name }}
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
                    class="flex-1 border px-2 py-2 rounded h-13"
                    required>
                <button type="submit" class="min-w-[80px] bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex justify-center items-center h-13">
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

                        {{-- 数量（クリックして手動入力） --}}
                        <form method="POST" action="{{ route('items.updateQuantity', [$genre->id, $item->id]) }}" class="inline-block w-16">
                            @csrf
                            @method('PATCH')
                            <input
                                type="number"
                                name="quantity"
                                value="{{ $item->quantity }}"
                                min="0"
                                class="w-full text-center border rounded px-1 py-0.5 focus:outline-none focus:ring focus:ring-blue-300"
                                onchange="this.form.submit()">
                        </form>

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
