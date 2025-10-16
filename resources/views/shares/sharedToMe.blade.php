@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">あなたが共有されているユーザー一覧</h2>

    @if($sharedByUsers->isEmpty())
        <p>現在、共有を受けているユーザーはいません。</p>
    @else
        <ul class="space-y-2">
            @foreach($sharedByUsers as $share)
                <li class="border p-3 rounded">
                    <div>
                        <span class="font-semibold">{{ $share->ownerUser->name ?? '---' }}</span>
                        <span class="text-sm text-gray-500 ml-2">({{ $share->ownerUser->email ?? '' }})</span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
