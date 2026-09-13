<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $universities = University::orderBy('name')->get();
        return view('author.settings', compact('universities'));
    }

    public function update(Request $request, \App\Services\AvatarService $avatarService): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'preferred_name' => 'nullable|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_avatar'  => 'nullable',
            'phone_number'   => 'nullable|string|max:50',
            'university_id'  => 'nullable|exists:universities,id',
            'department'     => 'nullable|string|max:255',
            'author_bio'     => 'nullable|string|max:2000',
        ], [
            'avatar.image'   => 'The profile photo must be a valid image file.',
            'avatar.mimes'   => 'The profile photo must be a file of type: PNG or JPG.',
            'avatar.max'     => 'The profile photo may not be greater than 2MB in size.',
        ]);

        $updateData = collect($validated)->except(['avatar', 'remove_avatar'])->toArray();

        // Handle avatar removal
        if ($request->boolean('remove_avatar')) {
            $avatarService->delete($user->avatar_path);
            $updateData['avatar_path'] = null;
        }
        // Handle avatar upload and compression
        elseif ($request->hasFile('avatar')) {
            $path = $avatarService->uploadAndCompress($request->file('avatar'), $user->avatar_path);
            $updateData['avatar_path'] = $path;
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }
}
