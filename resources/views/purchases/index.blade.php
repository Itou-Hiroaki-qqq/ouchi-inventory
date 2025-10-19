@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">次回購入リスト</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($groupedPurchases->isNotEmpty())
        @php
            $firstGenre = $groupedPurchases->keys()->first();
            $activeTab = request('tab', $firstGenre); // クエリがあれば優先
        @endphp
        <div x-data="{ activeTab: @js($activeTab) }">
            {{-- タブボタン --}}
            <div class="flex overflow-x-auto border-b mb-6 space-x-4">
                @foreach ($groupedPurchases as $genreName => $purchases)
                    <button
                        @click="activeTab = '{{ $genreName }}'"
                        :class="activeTab === '{{ $genreName }}' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                        class="px-3 py-2 text-sm font-medium whitespace-nowrap">
                        {{ $genreName }}
                    </button>
                @endforeach
            </div>

            {{-- タブごとの中身 --}}
            @foreach ($groupedPurchases as $genreName => $purchases)
                <div x-show="activeTab === '{{ $genreName }}'" class="space-y-4 mt-4">
                    @if($purchases->isEmpty())
                        <p>このジャンルの次回購入アイテムはありません。</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($purchases as $purchase)
                                <li class="bg-white font-bold border p-3 rounded flex justify-between items-center">
                                    <span>{{ $purchase->item->name }}</span>

                                    <div class="flex gap-3">
                                        {{-- 購入済みボタン --}}
                                        <form method="POST" action="{{ route('purchases.complete', $purchase->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                                購入済み
                                            </button>
                                        </form>

                                        {{-- 購入やめボタン --}}
                                        <form method="POST" action="{{ route('purchases.destroy', $purchase->id) }}"
                                            onsubmit="return confirm('このアイテムをリストから削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 text-sm">
                                                購入やめ
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p>次回購入リストは空です。</p>
    @endif
</div>
@endsection
