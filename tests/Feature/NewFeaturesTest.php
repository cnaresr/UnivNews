<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_approved_page_has_logout_and_navigation_buttons()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_AUTHOR,
            'author_status' => User::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($user)->get(route('author.apply.confirmation'));
        $response->assertStatus(200);
        $response->assertSee('Log Out');
        $response->assertSee(route('logout'));
        $response->assertSee(route('home'));
        $response->assertSee('Back');
    }

    public function test_guest_auth_pages_have_top_home_and_back_navigation()
    {
        // Login page
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee(route('home'));
        $response->assertSee('Back');
        $response->assertSee('Home');

        // Register page
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee(route('home'));
        $response->assertSee('Back');
        $response->assertSee('Home');

        // Forgot password page
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertSee(route('home'));
        $response->assertSee('Back');
        $response->assertSee('Home');
    }

    public function test_transactional_layout_has_both_back_and_home_and_5_column_footer()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        
        // Header navigation
        $response->assertSee('BACK');
        $response->assertSee('HOME');
        $response->assertSee(route('home'));

        // Footer 5 columns parity
        $response->assertSee('NEWSLETTER');
        $response->assertSee('SOCIAL');
        $response->assertSee('ABOUT US');
        $response->assertSee('HELP & SUPPORT', false);
        $response->assertSee(route('page.about'));
        $response->assertSee(route('page.faq'));
        $response->assertSee(route('page.contact'));
        $response->assertSee(route('page.privacy'));
    }

    public function test_profile_view_has_author_program_and_help_cards()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PUBLIC,
            'author_status' => User::STATUS_NONE,
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);

        // Author Program card
        $response->assertSee('Author Program & Portal Access', false);
        $response->assertSee('Apply as Contributor');
        $response->assertSee(route('author.apply'));

        // Newsletter card removed as requested
        $response->assertDontSee('Kelola Langganan Newsletter');

        // Help & Legal links
        $response->assertSee('Help Center, Information & Policies', false);
        $response->assertSee('About Us');
        $response->assertSee('Frequently Asked Questions (FAQ)');
        $response->assertSee('Contact Editorial & Support', false);
        $response->assertSee('Privacy Policy');

        // Modern cards styling check
        $response->assertSee('Profile Information');
        $response->assertSee('Security & Password', false);
        $response->assertSee('Danger Zone');
        $response->assertSee('Permanently delete account?');
    }

    public function test_public_subpages_have_home_and_back_navigation()
    {
        $pages = [
            route('page.about'),
            route('page.faq'),
            route('page.contact'),
            route('page.privacy'),
        ];

        foreach ($pages as $pageUrl) {
            $response = $this->get($pageUrl);
            $response->assertStatus(200);
            $response->assertSee('Back');
            $response->assertSee('Home');
            $response->assertSee(route('home'));
        }
    }
}
