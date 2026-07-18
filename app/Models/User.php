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
    'telegram',
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

    /** XP reward when sending a swap request */
    public const XP_SEND_REQUEST = 30;

    /** XP reward when a swap request is accepted (each party) */
    public const XP_ACCEPT_REQUEST = 50;

    /** XP reward for completing onboarding */
    public const XP_ONBOARDING = 10;

    /**
     * XP thresholds per level (must match updateLevel).
     *
     * @return array<int, int>
     */
    public static function xpThresholds(): array
    {
        return [
            1  => 0,
            2  => 100,
            4  => 300,
            6  => 800,
            8  => 1500,
            10 => 2500,
            12 => 3500,
            14 => 5000,
            16 => 6500,
            18 => 8000,
            20 => 10000,
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
     * Resolve level from XP (same rules as updateLevel).
     */
    public function resolveLevelFromXp(?int $xp = null): int
    {
        $xp ??= (int) $this->xp;

        return match (true) {
            $xp >= 10000 => 20,
            $xp >= 8000  => 18,
            $xp >= 6500  => 16,
            $xp >= 5000  => 14,
            $xp >= 3500  => 12,
            $xp >= 2500  => 10,
            $xp >= 1500  => 8,
            $xp >= 800   => 6,
            $xp >= 300   => 4,
            $xp >= 100   => 2,
            default      => 1,
        };
    }

    /**
     * XP progress metadata for UI (level, badge, progress bar).
     *
     * @return array{
     *     xp: int,
     *     level: int,
     *     badge: array{icon: string, nama: string},
     *     xp_progress: int,
     *     next_level: int|null,
     *     next_level_xp: int|null,
     *     is_max_level: bool
     * }
     */
    public function xpMeta(): array
    {
        $xp = (int) $this->xp;
        $level = $this->resolveLevelFromXp($xp);
        $thresholds = self::xpThresholds();
        $currentLevelXp = $thresholds[$level] ?? 0;

        $nextLevel = null;
        $nextLevelXp = null;

        foreach (array_keys($thresholds) as $lvl) {
            if ($lvl > $level) {
                $nextLevel = $lvl;
                $nextLevelXp = $thresholds[$lvl];
                break;
            }
        }

        $isMaxLevel = $nextLevel === null;
        $xpIntoLevel = max(0, $xp - $currentLevelXp);
        $xpForNextLevel = $isMaxLevel ? 0 : max(1, $nextLevelXp - $currentLevelXp);
        $xpProgress = $isMaxLevel
            ? 100
            : min(100, (int) round(($xpIntoLevel / $xpForNextLevel) * 100));

        // Badge dari level terhitung (bukan kolom DB yang bisa stale)
        $previousLevel = $this->level;
        $this->level = $level;
        $badge = $this->badge;
        $this->level = $previousLevel;

        return [
            'xp' => $xp,
            'level' => $level,
            'badge' => $badge,
            'xp_progress' => $xpProgress,
            'next_level' => $nextLevel,
            'next_level_xp' => $nextLevelXp,
            'is_max_level' => $isMaxLevel,
        ];
    }

    /**
     * Update level based on XP
     */
    public function updateLevel(): void
    {
        $this->level = $this->resolveLevelFromXp();
        $this->save();
    }

    /**
     * Add XP and update level
     */
    public function addXp(int $amount): void
    {
        $this->xp = (int) $this->xp + $amount;
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