@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">ジャンル一覧</h1>

    @if(session('success'))
        <div class="p-2 mb-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- 新しいジャンルの追加フォーム -->
    <form method="POST" action="{{ route('genres.store') }}" class="mb-6">
        @csrf
        <div class="flex space-x-2">
            <input type="text" name="name" placeholder="新しいジャンル名" class="border px-2 py-1 w-full rounded" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">追加</button>
        </div>
        @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    </form>

    <!-- ジャンル一覧 -->
    <ul class="space-y-2">
        @foreach($genres as $genre)
            <li class="flex justify-between items-center bg-white p-2 rounded border">
                <div>
                    <span>{{ $genre->name }}</span>
                    @if ($genre->isShared)
                        <span class="ml-2 text-sm text-gray-500">（共有）</span>
                    @endif
                </div>
                <div class="flex space-x-2 text-sm">
                    @if ($genre->canEdit)
                        <!-- 編集リンク -->
                        <a href="{{ route('genres.edit', $genre->id) }}" class="text-blue-500 hover:underline">編集</a>
                    @endif

                    @if ($genre->canDelete)
                        <!-- 削除フォーム -->
                        <form method="POST" action="{{ route('genres.destroy', $genre->id) }}" onsubmit="return confirm('削除してよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">削除</button>
                        </form>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
