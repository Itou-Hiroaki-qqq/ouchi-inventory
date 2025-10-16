<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Share;
use App\Models\User;

class ShareController extends Controller
{
    /**
     * 自分がオーナーとして共有している一覧
     */
    public function index()
    {
        $user = Auth::user();
        $shares = $user->sharedWith()->with('sharedUser')->get();

        return view('shares.index', [
            'shares' => $shares,
        ]);
    }

    /**
     * 自分が他人から共有されている一覧
     */
    public function sharedToMe()
    {
        $user = Auth::user();
        $sharedByUsers = $user->sharedBy()->with('owner')->get();

        return view('shares.sharedToMe', [
            'sharedByUsers' => $sharedByUsers,
        ]);
    }

    /**
     * 共有を新しく追加する（共有元が他のユーザーを招待）
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'shared_user_email' => 'required|email|exists:users,email',
        ]);

        $sharedUser = User::where('email', $request->shared_user_email)->first();

        if ($sharedUser->id === $user->id) {
            return back()->with('error', '自分自身を共有先にすることはできません。');
        }

        $exists = Share::where('owner_id', $user->id)
            ->where('shared_user_id', $sharedUser->id)
            ->exists();

        if (!$exists) {
            Share::create([
                'owner_id' => $user->id,
                'shared_user_id' => $sharedUser->id,
            ]);
        }

        return back()->with('success', '共有先を追加しました。');
    }

    /**
     * 共有を解除する（オーナーまたは共有された側）
     */
    public function destroy(Share $share)
    {
        $user = Auth::user();

        if ($share->owner_id !== $user->id && $share->shared_user_id !== $user->id) {
            abort(403);
        }

        $share->delete();

        return back()->with('success', '共有を解除しました。');
    }
}
