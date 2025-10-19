@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">ジャンル編集</h2>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- 編集フォーム --}}
    <form method="POST" action="{{ route('genres.update', $genre->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">ジャンル名</label>
            <input type="text" name="name" value="{{ old('name', $genre->name) }}"
                class="w-full border px-3 py-2 rounded" required>
            @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">更新</button>
            <a href="{{ route('genres.index') }}" class="text-gray-600 hover:underline">戻る</a>
        </div>
    </form>
</div>
@endsection