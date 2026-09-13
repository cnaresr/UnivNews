<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\AuthorApprovalToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SetPasswordController extends Controller
{
    /**
     * Show the set-password form (token-based, no auth required).
     */
    public function show(Request $request): View|RedirectResponse
    {
        $tokenRecord = AuthorApprovalToken::where('token', $request->query('token'))->first();

        if (! $tokenRecord) {
            return redirect()->route('home')->with('error', 'Your author approval has been cancelled or the link is no longer valid.');
        }

        if ($tokenRecord->isExpired()) {
            return view('author.token-expired', ['user' => $tokenRecord->user]);
        }

        if (!$tokenRecord->user || $tokenRecord->user->role !== User::ROLE_AUTHOR || $tokenRecord->user->author_status !== User::STATUS_APPROVED) {
            return redirect()->route('home')->with('error', 'Your author approval has been cancelled.');
        }

        return view('author.set-password', [
            'token' => $tokenRecord->token,
            'user'  => $tokenRecord->user,
        ]);
    }

    /**
     * Process the new password submission.
     */
    public function store(Request $request): RedirectResponse|View
    {
        $request->validate([
            'token'    => 'required|string',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.letters'   => 'Password harus mengandung huruf.',
            'password.mixed'     => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'   => 'Password harus mengandung angka.',
            'password.symbols'   => 'Password harus mengandung karakter spesial (contoh: !@#$%).',
        ]);

        $tokenRecord = AuthorApprovalToken::where('token', $request->token)->first();

        if (! $tokenRecord) {
            return redirect()->route('home')->with('error', 'Your author approval has been cancelled or the link is no longer valid.');
        }

        if ($tokenRecord->isExpired()) {
            return view('author.token-expired', ['user' => $tokenRecord->user]);
        }

        $user = $tokenRecord->user;

        if (!$user || $user->role !== User::ROLE_AUTHOR || $user->author_status !== User::STATUS_APPROVED) {
            return redirect()->route('home')->with('error', 'Your author approval has been cancelled.');
        }

        // Set the password and mark as fully activated
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the used token
        $tokenRecord->delete();

        return redirect()->route('login')->with('success', '🎉 Password successfully created! Your author account is now active. Please log in.');
    }

    /**
     * Resend a new activation link for an expired token (user must provide their email).
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', User::ROLE_AUTHOR)
                    ->where('author_status', User::STATUS_APPROVED)
                    ->first();

        if (! $user) {
            return redirect()->route('home')->with('error', 'Email was not found or your author approval has been cancelled.');
        }

        // Delete old token and create fresh one
        $user->approvalToken()->delete();
        $token = \App\Models\AuthorApprovalToken::create([
            'user_id'    => $user->id,
            'token'      => \Illuminate\Support\Str::random(64),
            'expires_at' => now()->addHours(48),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\AuthorApplicationApproved($user, $token));
        } catch (\Exception $e) {
            // Log, don't block
        }

        return back()->with('success', 'Link aktivasi baru telah dikirim ke email kamu. Silakan cek inbox/spam.');
    }
}
