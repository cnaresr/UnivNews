<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AvatarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_reader_can_upload_png_avatar_and_it_is_compressed_to_jpeg(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PUBLIC,
        ]);

        $file = UploadedFile::fake()->image('my_avatar.png', 800, 600);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        $this->assertStringEndsWith('.jpg', $user->avatar_path);
        $this->assertTrue(Storage::disk('public')->exists($user->avatar_path));

        // Verify dimensions are constrained to max 500x500
        $contents = Storage::disk('public')->get($user->avatar_path);
        $image = imagecreatefromstring($contents);
        $this->assertNotFalse($image);
        $this->assertLessThanOrEqual(500, imagesx($image));
        $this->assertLessThanOrEqual(500, imagesy($image));
        imagedestroy($image);
    }

    public function test_avatar_exceeding_2mb_is_rejected(): void
    {
        $user = User::factory()->create();

        // 2500 KB > 2048 KB limit
        $file = UploadedFile::fake()->create('huge_photo.jpg', 2500, 'image/jpeg');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $file,
            ]);

        $response->assertSessionHasErrors('avatar');
        $user->refresh();
        $this->assertNull($user->avatar_path);
    }

    public function test_avatar_with_invalid_format_is_rejected(): void
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $file,
            ]);

        $response->assertSessionHasErrors('avatar');
        $user->refresh();
        $this->assertNull($user->avatar_path);
    }

    public function test_user_can_remove_avatar(): void
    {
        $avatarService = app(AvatarService::class);
        $file = UploadedFile::fake()->image('test.jpg', 200, 200);
        $storedPath = $avatarService->uploadAndCompress($file);

        $user = User::factory()->create([
            'avatar_path' => $storedPath,
        ]);

        $this->assertTrue(Storage::disk('public')->exists($storedPath));

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'remove_avatar' => '1',
            ]);

        $response->assertSessionHasNoErrors();
        $user->refresh();
        $this->assertNull($user->avatar_path);
        $this->assertFalse(Storage::disk('public')->exists($storedPath));
    }

    public function test_admin_can_upload_avatar_and_remove_it(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $file = UploadedFile::fake()->image('admin_photo.jpg', 400, 400);

        $response = $this
            ->actingAs($admin, 'admin')
            ->from(route('admin.settings.edit'))
            ->put(route('admin.settings.update'), [
                'name' => 'Admin Boss',
                'email' => $admin->email,
                'avatar' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.settings.edit'));

        $admin->refresh();
        $this->assertNotNull($admin->avatar_path);
        $this->assertTrue(Storage::disk('public')->exists($admin->avatar_path));

        // Remove avatar as admin
        $removeResponse = $this
            ->actingAs($admin, 'admin')
            ->from(route('admin.settings.edit'))
            ->put(route('admin.settings.update'), [
                'name' => 'Admin Boss',
                'email' => $admin->email,
                'remove_avatar' => '1',
            ]);

        $removeResponse->assertSessionHasNoErrors();
        $admin->refresh();
        $this->assertNull($admin->avatar_path);
    }

    public function test_author_can_upload_avatar(): void
    {
        $author = User::factory()->create([
            'role' => User::ROLE_AUTHOR,
            'author_status' => User::STATUS_APPROVED,
        ]);

        $file = UploadedFile::fake()->image('author_photo.png', 350, 350);

        $response = $this
            ->actingAs($author)
            ->from(route('author.settings.edit'))
            ->put(route('author.settings.update'), [
                'name' => 'Author Writer',
                'email' => $author->email,
                'avatar' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('author.settings.edit'));

        $author->refresh();
        $this->assertNotNull($author->avatar_path);
        $this->assertTrue(Storage::disk('public')->exists($author->avatar_path));
    }
}
