<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewApplicationNotification;
use App\Mail\AuthorApplicationReceived;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ApplyController extends Controller
{
    public function create(): View
    {
        $user = auth()->user();

        $universities = University::orderBy('name')->get();
        return view('author.apply', compact('universities', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Already an active author — redirect to dashboard
        if ($user->author_status === User::STATUS_APPROVED) {
            return redirect()->route('author.dashboard')->with('info', 'You are already an approved author.');
        }

        // Already pending — no need to re-submit
        if ($user->author_status === User::STATUS_PENDING) {
            return redirect()->route('author.apply')->with('info', 'Your application is already under review.');
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'university_id' => 'required|exists:universities,id',
            'department'    => 'required|string|max:255',
            'page_name'     => 'nullable|string|max:255',
            'phone_number'  => 'nullable|string|max:50',
            'author_bio'    => 'required|string|min:50|max:2000',
        ], [
            'author_bio.min'  => 'Bio / statement harus minimal 50 karakter.',
            'author_bio.required' => 'Bio / statement wajib diisi.',
            'university_id.required' => 'Universitas wajib dipilih.',
            'department.required' => 'Fakultas / Departemen wajib diisi.',
        ]);

        $user->update([
            'name'              => $validated['name'],
            'university_id'     => $validated['university_id'],
            'department'        => $validated['department'],
            'page_name'         => $validated['page_name'] ?? $user->page_name,
            'phone_number'      => $validated['phone_number'] ?? $user->phone_number,
            'author_bio'        => $validated['author_bio'],
            'author_status'     => User::STATUS_PENDING,
            'author_applied_at' => now(),
        ]);

        // Send confirmation email to the applicant
        try {
            Mail::to($user->email)->send(new AuthorApplicationReceived($user->fresh()));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send author application email: ' . $e->getMessage());
        }

        // Notify all admins
        try {
            $adminEmails = User::where('role', User::ROLE_ADMIN)->pluck('email')->toArray();
            $fallbackAdminEmail = env('ADMIN_EMAIL');

            if ($fallbackAdminEmail && !in_array($fallbackAdminEmail, $adminEmails)) {
                $adminEmails[] = $fallbackAdminEmail;
            }

            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new AdminNewApplicationNotification($user->fresh()));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send admin notification email: ' . $e->getMessage());
        }

        return redirect()->route('author.apply.confirmation');
    }

    public function confirmation(): View|RedirectResponse
    {
        $user = auth()->user();

        // If approved but accessing this page (maybe hasn't set password yet or just checking status)
        if ($user->author_status === User::STATUS_APPROVED) {
            return view('author.apply-approved', compact('user'));
        }

        if ($user->author_status === User::STATUS_PENDING) {
            return view('author.apply-confirmation', compact('user'));
        }

        // If approval was cancelled or status is none/rejected, redirect to main page/dashboard
        return redirect()->route('dashboard')->with('info', 'Your author application is not active or approval was cancelled.');
    }
}
