<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin as Admin;
use App\Http\Controllers\Author as Author;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

// 1. Public Portal Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/events', [PublicController::class, 'events'])->name('events');
Route::get('/achievements', [PublicController::class, 'achievements'])->name('achievements');
Route::get('/category/{category:slug}', [PublicController::class, 'category'])->name('category');
Route::get('/research', [PublicController::class, 'research'])->name('research');
Route::get('/article/{article:slug}', [PublicController::class, 'article'])->name('article');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::get('/tag/{name}', [PublicController::class, 'tag'])->name('tag');

// Site Content Public Pages
Route::get('/about-us', [\App\Http\Controllers\PageController::class, 'aboutUs'])->name('page.about');
Route::get('/help/faq', [\App\Http\Controllers\PageController::class, 'faq'])->name('page.faq');
Route::get('/help/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('page.contact');
Route::get('/privacy-policy', [\App\Http\Controllers\PageController::class, 'privacyPolicy'])->name('page.privacy');

// Public API endpoints
Route::get('/api/homepage/featured', [\App\Http\Controllers\HomepageController::class, 'featured'])->name('api.homepage.featured');

// 2. Authenticated General Routes
Route::middleware(['auth'])->group(function () {
    
    // Generic Dashboard fallback route (untuk web guard: author/reader)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAuthor()) {
            return redirect()->route('author.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Standard Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Apply to Become an Author
    Route::get('/apply-author', [Author\ApplyController::class, 'create'])->name('author.apply');
    Route::post('/apply-author', [Author\ApplyController::class, 'store'])->name('author.apply.store');
    Route::get('/apply-author/confirmation', [Author\ApplyController::class, 'confirmation'])->name('author.apply.confirmation');

    // 3. Author Area Routes
    Route::prefix('author')->middleware(['role:author'])->name('author.')->group(function () {
        Route::get('/dashboard', [Author\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('articles', Author\ArticleController::class)->except(['show']);
        Route::get('/profile', [Author\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [Author\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/settings', [Author\ProfileController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [Author\ProfileController::class, 'update'])->name('settings.update');

        // Boost Routes
        Route::get('/articles/{article}/boost', [Author\BoostController::class, 'create'])->name('articles.boost');
        Route::post('/articles/{article}/boost', [Author\BoostController::class, 'store'])->name('articles.boost.store');
        Route::get('/articles/{article}/boost/availability', [Author\BoostController::class, 'availability'])->name('articles.boost.availability');
    });



    // 5. Payment Routes (author only — artikel harus awaiting_payment)
    Route::get('/payment/{article}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{article}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/{article}/thanks', [PaymentController::class, 'thanks'])->name('payment.thanks');

    // Onboarding completion (web guard users: author)
    Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');

    // ── Engagement Routes ──────────────────────────────────────────────────

    // Like toggle (auth required; guest → redirect to login)
    Route::post('/articles/{article}/like', [LikeController::class, 'toggle'])
        ->name('articles.like.toggle');

    // Comment store (auth + throttle: 6 req/min ≈ 1 per 10 sec)
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('articles.comments.store');

    // Comment soft-delete (auth required)
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

// 5. Auth Routes (Breeze)
require __DIR__.'/auth.php';

// 6. Google OAuth Routes
Route::middleware('guest.admin_aware')->group(function () {
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

// 7. Admin Login Routes
Route::middleware('guest.admin_aware')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sign-in', [AdminLoginController::class, 'create'])->name('login');
    Route::post('/sign-in', [AdminLoginController::class, 'store']);
});

// 7b. Admin Logout Route (tidak perlu middleware role, hanya butuh CSRF)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
});

// 7c. Admin Area Routes (menggunakan admin guard terpisah via role:admin middleware)
Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Onboarding completion (admin guard users)
    Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
    
    // Article Review Workflow
    Route::get('/articles/{article}/review', [Admin\ReviewController::class, 'show'])->name('articles.review');
    Route::post('/articles/{article}/approve', [Admin\ReviewController::class, 'approve'])->name('articles.approve');
    Route::post('/articles/{article}/reject', [Admin\ReviewController::class, 'reject'])->name('articles.reject');

    // Articles Resource
    Route::resource('articles', Admin\ArticleController::class)->except(['show']);

    // Author Management
    Route::get('/authors', [Admin\AuthorManagementController::class, 'index'])->name('authors.index');
    Route::post('/authors/{user}/approve', [Admin\AuthorManagementController::class, 'approve'])->name('authors.approve');
    Route::post('/authors/{user}/reject', [Admin\AuthorManagementController::class, 'reject'])->name('authors.reject');
    Route::post('/authors/{user}/suspend', [Admin\AuthorManagementController::class, 'suspend'])->name('authors.suspend');
    Route::post('/authors/{user}/cancel-approval', [Admin\AuthorManagementController::class, 'cancelApproval'])->name('authors.cancel-approval');
    Route::delete('/authors/{user}', [Admin\AuthorManagementController::class, 'destroy'])->name('authors.destroy');

    // University Management
    Route::resource('universities', Admin\UniversityController::class)->only(['index', 'store', 'destroy']);

    // Profile & Settings (User Profile)
    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [Admin\ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/password', [Admin\ProfileController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings', [Admin\ProfileController::class, 'update'])->name('settings.update');

    // Active Sessions
    Route::get('/sessions', [Admin\SessionController::class, 'index'])->name('sessions.index');
    Route::post('/sessions/gps-location', [Admin\SessionController::class, 'updateGps'])->name('sessions.update-gps');
    Route::delete('/sessions/{id}', [Admin\SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::post('/sessions/revoke-others', [Admin\SessionController::class, 'destroyOthers'])->name('sessions.destroy-others');

    // App Settings (Payment Fee, etc.)
    Route::get('/app-settings', [Admin\AppSettingsController::class, 'index'])->name('app-settings.index');
    Route::put('/app-settings', [Admin\AppSettingsController::class, 'update'])->name('app-settings.update');
    
    // Boost Prices Management (API endpoints for admin panel)
    Route::get('/api/boost-prices', [Admin\BoostPriceController::class, 'index'])->name('api.boost-prices.index');
    Route::put('/api/boost-prices/{boostPrice}', [Admin\BoostPriceController::class, 'update'])->name('api.boost-prices.update');
    Route::delete('/boost-prices/{boostPrice}', [Admin\AppSettingsController::class, 'destroyBoostPrice'])->name('boost-prices.destroy');

    // Site Content Management
    Route::get('/pages/about', [Admin\PageController::class, 'editAbout'])->name('pages.about.edit');
    Route::put('/pages/about', [Admin\PageController::class, 'updateAbout'])->name('pages.about.update');
    Route::get('/pages/privacy', [Admin\PageController::class, 'editPrivacy'])->name('pages.privacy.edit');
    Route::put('/pages/privacy', [Admin\PageController::class, 'updatePrivacy'])->name('pages.privacy.update');
    Route::resource('faqs', Admin\FaqController::class)->except(['show']);
    Route::get('/pages/contact', [Admin\ContactController::class, 'edit'])->name('pages.contact.edit');
    Route::put('/pages/contact', [Admin\ContactController::class, 'update'])->name('pages.contact.update');
});

// 8. Author Password Setup (token-based, no auth required)
Route::get('/author/set-password', [Author\SetPasswordController::class, 'show'])->name('author.set-password.show');
Route::post('/author/set-password', [Author\SetPasswordController::class, 'store'])->name('author.set-password.store');
Route::post('/author/set-password/resend', [Author\SetPasswordController::class, 'resend'])->name('author.set-password.resend');

// 9. Webhook — exclude dari CSRF di bootstrap/app.php
// Endpoint ini dipanggil oleh server Mayar (bukan browser), sehingga tidak pakai session/CSRF.
Route::post('/webhooks/mayar', [WebhookController::class, 'handleMayar'])->name('webhooks.mayar');
Route::post('/webhooks/mayar/boost', [WebhookController::class, 'handleMayarBoost'])->name('webhooks.mayar.boost');

// 10. Fallback Storage Route
// Ensures public storage files are always served even if symlinks are disabled or unsupported
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeType = match ($ext) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png'         => 'image/png',
        'gif'         => 'image/gif',
        'webp'        => 'image/webp',
        'svg'         => 'image/svg+xml',
        default       => mime_content_type($filePath) ?: 'application/octet-stream',
    };
    return response()->file($filePath, [
        'Content-Type'  => $mimeType,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.fallback');

