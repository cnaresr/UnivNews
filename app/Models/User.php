<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_AUTHOR = 'author';
    const ROLE_PUBLIC = 'public';

    const STATUS_NONE = 'none';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'university_id',
        'name',
        'preferred_name',
        'email',
        'phone_number',
        'department',
        'password',
        'role',
        'author_status',
        'author_bio',
        'page_name',
        'avatar_path',
        'social_links',
        'provider',
        'provider_id',
        'author_applied_at',
        'author_rejection_reason',
        'has_completed_onboarding',
        'onboarding_completed_at',
        'completed_page_tours',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'          => 'datetime',
            'author_applied_at'          => 'datetime',
            'onboarding_completed_at'    => 'datetime',
            'password'                   => 'hashed',
            'social_links'               => 'array',
            'has_completed_onboarding'   => 'boolean',
            'completed_page_tours'       => 'array',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function approvalToken()
    {
        return $this->hasOne(AuthorApprovalToken::class);
    }

    public function boosts()
    {
        return $this->hasMany(Boost::class);
    }

    public function boostPayments()
    {
        return $this->hasMany(BoostPayment::class);
    }

    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    // ── Role Helpers ───────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAuthor(): bool
    {
        return $this->role === self::ROLE_AUTHOR;
    }

    public function isPublic(): bool
    {
        return $this->role === self::ROLE_PUBLIC;
    }

    // ── Author Status Helpers ──────────────────────────────────────────────

    public function isAuthorApproved(): bool
    {
        return $this->isAuthor() && $this->author_status === self::STATUS_APPROVED;
    }

    public function isAuthorPending(): bool
    {
        return $this->author_status === self::STATUS_PENDING;
    }

    public function isAuthorRejected(): bool
    {
        return $this->author_status === self::STATUS_REJECTED;
    }

    // ── Avatar Helpers ─────────────────────────────────────────────────────

    /**
     * Check whether the user has a custom avatar.
     */
    public function hasAvatar(): bool
    {
        return !empty($this->avatar_path);
    }

    /**
     * Get the public URL for the user's avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar_path)) {
            return '';
        }

        if (\Illuminate\Support\Str::startsWith($this->avatar_path, ['http://', 'https://'])) {
            return $this->avatar_path;
        }

        $cleanPath = ltrim($this->avatar_path, '/');
        if (\Illuminate\Support\Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        return '/storage/' . $cleanPath;
    }

    // ── Onboarding Helpers ─────────────────────────────────────────────────

    /**
     * Check whether a per-page tour has already been completed by this user.
     *
     * @param string $tourId  e.g. 'author.articles.create'
     */
    public function hasCompletedPageTour(string $tourId): bool
    {
        $tours = $this->completed_page_tours ?? [];
        return in_array($tourId, $tours, true);
    }
}

