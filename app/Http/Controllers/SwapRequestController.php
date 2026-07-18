<?php

namespace App\Http\Controllers;

use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwapRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $incoming = SwapRequest::where('receiver_id', $user->id)
            ->with(['sender.userSkills.skill', 'skill'])
            ->orderBy('created_at', 'desc')
            ->get();

        $outgoing = SwapRequest::where('sender_id', $user->id)
            ->with(['receiver.userSkills.skill', 'skill'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('swap.index', compact('incoming', 'outgoing'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'skill_id'    => 'required|exists:skills,id',
        ], [
            'receiver_id.required' => 'User tujuan wajib dipilih.',
            'receiver_id.exists'   => 'User tidak ditemukan.',
            'skill_id.required'    => 'Skill wajib dipilih.',
            'skill_id.exists'      => 'Skill tidak ditemukan.',
        ]);

        $user = Auth::user();

        if ($user->id == $request->receiver_id) {
            return $this->swapResponse($request, false, 'Tidak bisa mengirim request ke diri sendiri.');
        }

        $existing = SwapRequest::where('sender_id', $user->id)
            ->where('receiver_id', $request->receiver_id)
            ->where('skill_id', $request->skill_id)
            ->whereIn('status', ['menunggu', 'diterima'])
            ->first();

        if ($existing) {
            return $this->swapResponse($request, false, 'Kamu sudah mengirim request skill ini ke user tersebut.');
        }

        SwapRequest::create([
            'sender_id'   => $user->id,
            'receiver_id' => $request->receiver_id,
            'skill_id'    => $request->skill_id,
            'status'      => 'menunggu',
        ]);

        $xpGained = User::XP_SEND_REQUEST;
        $user->addXp($xpGained);
        $user->refresh();

        return $this->swapResponse(
            $request,
            true,
            'Request berhasil dikirim! +' . $xpGained . ' XP',
            [
                'xp_gained' => $xpGained,
                'user' => $user->xpMeta(),
            ]
        );
    }

    public function accept(Request $request, $id)
    {
        $user        = Auth::user();
        $swapRequest = SwapRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $swapRequest->update(['status' => 'diterima']);

        $xpGained = User::XP_ACCEPT_REQUEST;
        $user->addXp($xpGained);
        $swapRequest->sender->addXp($xpGained);
        $user->refresh();

        return $this->swapResponse(
            $request,
            true,
            'Request diterima! +' . $xpGained . ' XP',
            [
                'xp_gained' => $xpGained,
                'user' => $user->xpMeta(),
            ]
        );
    }

    public function reject(Request $request, $id)
    {
        $user        = Auth::user();
        $swapRequest = SwapRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $swapRequest->update(['status' => 'ditolak']);

        return $this->swapResponse($request, true, 'Request ditolak!');
    }

    /**
     * JSON for AJAX/dashboard, flash redirect for classic form posts.
     */
    private function swapResponse(Request $request, bool $success, string $message, array $extra = [])
    {
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge([
                'success' => $success,
                'message' => $message,
            ], $extra), $success ? 200 : 422);
        }

        $flashKey = $success ? 'success' : 'error';

        return back()->with($flashKey, $message);
    }

    public function cancel($id)
    {
        $user        = Auth::user();
        $swapRequest = SwapRequest::where('id', $id)
            ->where('sender_id', $user->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $swapRequest->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Request dibatalkan!');
    }
}