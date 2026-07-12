<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'bio',
    'photo',
    'university',
    'major',
    'semester',
    'city',
    'xp',
    'level',
    'is_onboarded',
    'whatsapp',
    'discord',
])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_onboarded' => 'boolean',
        ];
    }

    /**
     * Get badge based on level
     */
    public function getBadgeAttribute(): array
    {
        if ($this->level >= 20) return ['icon' => 'bi bi-trophy-fill',      'nama' => 'Legenda'];
        if ($this->level >= 18) return ['icon' => 'bi bi-gem',              'nama' => 'Grand Master'];
        if ($this->level >= 16) return ['icon' => 'bi bi-award-fill',       'nama' => 'Elite'];
        if ($this->level >= 14) return ['icon' => 'bi bi-star-fill',        'nama' => 'Expert'];
        if ($this->level >= 12) return ['icon' => 'bi bi-lightning-fill',   'nama' => 'Master'];
        if ($this->level >= 10) return ['icon' => 'bi bi-shield-fill',      'nama' => 'Veteran'];
        if ($this->level >= 8)  return ['icon' => 'bi bi-mortarboard-fill', 'nama' => 'Mentor'];
        if ($this->level >= 6)  return ['icon' => 'bi bi-book-fill',        'nama' => 'Pengajar'];
        if ($this->level >= 4)  return ['icon' => 'bi bi-journal-check',    'nama' => 'Pelajar'];
        if ($this->level >= 2)  return ['icon' => 'bi bi-person-check',     'nama' => 'Pemula'];
        return                         ['icon' => 'bi bi-person',           'nama' => 'Newbie'];
    }

    /**
     * Update level based on XP
     */
    public function updateLevel(): void
    {
        $this->level = match(true) {
            $this->xp >= 10000 => 20,
            $this->xp >= 8000  => 18,
            $this->xp >= 6500  => 16,
            $this->xp >= 5000  => 14,
            $this->xp >= 3500  => 12,
            $this->xp >= 2500  => 10,
            $this->xp >= 1500  => 8,
            $this->xp >= 800   => 6,
            $this->xp >= 300   => 4,
            $this->xp >= 100   => 2,
            default            => 1,
        };
        $this->save();
    }

    /**
     * Add XP and update level
     */
    public function addXp(int $amount): void
    {
        $this->xp += $amount;
        $this->updateLevel();
    }

    /**
     * Relasi ke user_skills
     */
    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Relasi ke swap_requests sebagai sender
     */
    public function sentRequests()
    {
        return $this->hasMany(SwapRequest::class, 'sender_id');
    }

    /**
     * Relasi ke swap_requests sebagai receiver
     */
    public function receivedRequests()
    {
        return $this->hasMany(SwapRequest::class, 'receiver_id');
    }
}