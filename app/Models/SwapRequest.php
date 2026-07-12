<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwapRequest extends Model
{
    /** @use HasFactory<\Database\Factories\SwapRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'skill_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relasi ke user sebagai sender
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi ke user sebagai receiver
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Relasi ke skill
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}