<?php

namespace Tests\Feature;

use App\Models\AuthorApprovalToken;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CancelAuthorApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $reader;
    protected University $university;

    protected function setUp(): void
    {
        parent::setUp();

        $this->university = University::create([
            'name' => 'Diponegoro University',
            'slug' => 'undip',
        ]);

        $this->admin = User::factory()->create([
            'role'          => User::ROLE_ADMIN,
            'author_status' => User::STATUS_APPROVED,
        ]);

        $this->reader = User::factory()->create([
            'role'          => User::ROLE_PUBLIC,
            'author_status' => User::STATUS_NONE,
            'university_id' => $this->university->id,
        ]);
    }

    public function test_admin_can_cancel_approval_for_unverified_author_applicant(): void
    {
        // Simulate reader applied and was approved by admin
        $this->reader->update([
            'role'          => User::ROLE_AUTHOR,
            'author_status' => User::STATUS_APPROVED,
        ]);

        $token = AuthorApprovalToken::create([
            'user_id'    => $this->reader->id,
            'token'      => Str::random(64),
            'expires_at' => now()->addHours(48),
        ]);

        $this->assertDatabaseHas('author_approval_tokens', ['user_id' => $this->reader->id]);

        // Admin cancels the approval
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.authors.cancel-approval', $this->reader));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check user reverted to reader
        $this->reader->refresh();
        $this->assertEquals(User::ROLE_PUBLIC, $this->reader->role);
        $this->assertEquals(User::STATUS_NONE, $this->reader->author_status);

        // Check token deleted
        $this->assertDatabaseMissing('author_approval_tokens', ['user_id' => $this->reader->id]);
    }

    public function test_user_with_cancelled_approval_is_redirected_on_subsequent_actions(): void
    {
        // Create token, then simulate admin cancelling it
        $tokenString = Str::random(64);

        // Reader tries to visit /author/set-password with no valid token or cancelled token
        $response = $this->get(route('author.set-password.show', ['token' => $tokenString]));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');

        // Reader visits /apply-author/confirmation while logged in
        $response = $this->actingAs($this->reader)->get(route('author.apply.confirmation'));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_cannot_cancel_approval_if_user_already_completed_setup(): void
    {
        // Fully active author without approval token
        $author = User::factory()->create([
            'role'          => User::ROLE_AUTHOR,
            'author_status' => User::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.authors.cancel-approval', $author));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $author->refresh();
        $this->assertEquals(User::ROLE_AUTHOR, $author->role);
    }

    public function test_non_admin_cannot_cancel_author_approval(): void
    {
        $response = $this->actingAs($this->reader)
            ->post(route('admin.authors.cancel-approval', $this->reader));

        $response->assertRedirect(route('admin.login'));
    }
}
