@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">共有ユーザー一覧</h2>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="mb-6">
        <form method="POST" action="{{ route('shares.store') }}" class="flex space-x-2">
            @csrf
            <input type="email" name="shared_user_email" placeholder="共有するユーザーのメールアドレス"
                class="border px-3 py-2 rounded w-full" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">共有を追加</button>
        </form>
        @error('shared_user_email')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    @if($shares->isEmpty())
    <p>現在、共有ユーザーはいません。</p>
    @else
    <ul class="space-y-2">
        @foreach($shares as $share)
        <li class="border p-3 rounded flex justify-between items-center">
            <div>
                {{-- 共有先ユーザーの情報表示 --}}
                <span>{{ $share->sharedUser->name ?? '---' }}</span>
                <span class="text-sm text-gray-500 ml-2">({{ $share->sharedUser->email ?? '' }})</span>
            </div>
            <form method="POST" action="{{ route('shares.destroy', $share->id) }}"
                onsubmit="return confirm('この共有を解除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:underline text-sm">解除</button>
            </form>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection