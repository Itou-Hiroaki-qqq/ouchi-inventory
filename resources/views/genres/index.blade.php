@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">ジャンル一覧</h1>

    @if(session('success'))
        <div class="p-2 mb-4 bg-green-100 border border-green-400 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('genres.store') }}" class="mb-6">
        @csrf
        <div class="flex space-x-2">
            <input type="text" name="name" placeholder="新しいジャンル名" class="border px-2 py-1 w-full" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">追加</button>
        </div>
        @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    </form>

    <ul class="list-disc pl-5">
        @foreach($genres as $genre)
            <li>{{ $genre->name }}</li>
        @endforeach
    </ul>
</div>
@endsection
