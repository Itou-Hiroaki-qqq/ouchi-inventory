@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">

    {{-- 商品名（タイトル表示） --}}
    <h1 class="text-3xl font-bold mb-8 text-center">{{ $item->name }}</h1>

    {{-- 成功メッセージ --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6 text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- 備考メモ編集フォーム --}}
    <form method="POST" action="{{ route('items.update', [$genre->id, $item->id]) }}" class="mb-12">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label class="block font-semibold mb-2 text-lg">備考メモ</label>
            <textarea
                name="note"
                rows="6"
                class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                placeholder="この商品のメモを入力してください"
            >{{ old('note', $item->note) }}</textarea>
            @error('note')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-center">
            <button
                type="submit"
                class="bg-blue-600 text-white px-8 py-2 rounded hover:bg-blue-700 transition"
            >
                作成（保存）
            </button>
        </div>
    </form>

    {{-- 完全削除ボタン --}}
    <form
        method="POST"
        action="{{ route('items.destroy', [$genre->id, $item->id]) }}"
        onsubmit="return confirm('本当にこの商品を完全に削除しますか？この操作は取り消せません。');"
        class="text-center"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition"
        >
            この商品を完全削除する
        </button>
    </form>

</div>
@endsection
