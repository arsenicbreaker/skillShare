<?php

namespace App\Http\Controllers;

use App\Models\SwapRequest;
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
            return back()->with('error', 'Tidak bisa mengirim request ke diri sendiri.');
        }

        $existing = SwapRequest::where('sender_id', $user->id)
            ->where('receiver_id', $request->receiver_id)
            ->where('skill_id', $request->skill_id)
            ->whereIn('status', ['menunggu', 'diterima'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah mengirim request skill ini ke user tersebut.');
        }

        SwapRequest::create([
            'sender_id'   => $user->id,
            'receiver_id' => $request->receiver_id,
            'skill_id'    => $request->skill_id,
            'status'      => 'menunggu',
        ]);

        $user->addXp(30);

        return back()->with('success', 'Request berhasil dikirim!');
    }

    public function accept($id)
    {
        $user        = Auth::user();
        $swapRequest = SwapRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $swapRequest->update(['status' => 'diterima']);

        $user->addXp(50);
        $swapRequest->sender->addXp(50);

        return back()->with('success', 'Request diterima!');
    }

    public function reject($id)
    {
        $user        = Auth::user();
        $swapRequest = SwapRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $swapRequest->update(['status' => 'ditolak']);

        return back()->with('success', 'Request ditolak!');
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