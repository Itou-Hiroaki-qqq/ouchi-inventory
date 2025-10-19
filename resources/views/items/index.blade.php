@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6">
    <h1 class="text-xl font-bold mb-4">
        「{{ $genre->name }}」のアイテム一覧
        @if (!empty($isShared) && $isShared)
            <span class="text-sm text-gray-500 ml-2">（共有）</span>
        @endif
    </h1>

    @if(session('success'))
        <div class="p-2 mb-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- アイテム追加フォーム（オーナーだけが追加可能） --}}
    @if (!empty($canEditGenre) && $canEditGenre)
        <form method="POST" action="{{ route('items.store', $genre->id) }}" class="mb-6">
            @csrf
            <div class="flex space-x-2">
                <input type="text" name="name" placeholder="アイテム名" class="border px-2 py-1 w-full rounded" required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">追加</button>
            </div>
            @error('name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </form>
    @endif

    {{-- アイテム一覧表示 --}}
    <ul class="space-y-2">
        @forelse($items as $item)
        <li class="@if($item->quantity === 0) bg-gray-100 text-gray-400 @else bg-white @endif border p-3 rounded flex justify-between items-center">
            <div>
                <span class="font-semibold">{{ $item->name }}</span>

                {{-- 数量増減ボタン（共有ユーザーも可能） --}}
                <div class="flex items-center mt-1 gap-2">
                    <form method="POST" action="{{ route('items.decrement', [$genre->id, $item->id]) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-gray-300 px-2 py-1 rounded hover:bg-gray-400">−</button>
                    </form>

                    <span class="@if($item->quantity === 0) text-gray-400 @endif w-8 text-center">{{ $item->quantity }}</span>

                    <form method="POST" action="{{ route('items.increment', [$genre->id, $item->id]) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-green-300 px-2 py-1 rounded hover:bg-green-400">＋</button>
                    </form>
                </div>
            </div>

            <div class="flex gap-4 items-center">
                {{-- 編集リンク（オーナーのみ表示） --}}
                @if (!empty($item->canEdit) && $item->canEdit)
                    <a href="{{ route('items.edit', [$genre->id, $item->id]) }}" class="text-blue-500 hover:underline text-sm">編集</a>
                @endif

                {{-- 削除フォーム（オーナーのみ表示） --}}
                @if (!empty($item->canDelete) && $item->canDelete)
                    <form method="POST" action="{{ route('items.destroy', [$genre->id, $item->id]) }}"
                        onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 text-sm hover:underline">削除</button>
                    </form>
                @endif

                {{-- 次回購入ボタン（誰でも表示） --}}
                <form method="POST" action="{{ route('purchases.store', [$genre->id, $item->id]) }}">
                    @csrf
                    <button type="submit" class="text-orange-500 text-sm hover:underline">次回購入</button>
                </form>
            </div>
        </li>
        @empty
        <li>アイテムが登録されていません。</li>
        @endforelse
    </ul>
</div>
@endsection
