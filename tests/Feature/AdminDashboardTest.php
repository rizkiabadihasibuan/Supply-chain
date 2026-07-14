<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Port;
use App\Models\Role;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $analystUser;
    protected $country;
    protected $adminRole;
    protected $analystRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & lexicons
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        $this->adminRole = Role::where('name', 'Admin')->first();
        $this->analystRole = Role::where('name', 'Analyst')->first();

        $this->adminUser = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'name' => 'Admin User',
            'email' => 'admin@supplyrisk.io',
        ]);

        $this->analystUser = User::factory()->create([
            'role_id' => $this->analystRole->id,
            'name' => 'Analyst User',
            'email' => 'analyst@supplyrisk.io',
        ]);

        // Create country
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'region' => 'Europe',
            'language' => 'German',
        ]);
    }

    /**
     * Guest cannot access admin dashboard.
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Analyst cannot access admin dashboard (403).
     */
    public function test_analyst_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->analystUser)
            ->get(route('admin.dashboard'));
        
        $response->assertStatus(403);
    }

    /**
     * Admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));
        
        $response->assertStatus(200)
            ->assertSee('Admin Control Panel')
            ->assertSee('analyst@supplyrisk.io');
    }

    /**
     * Admin can update user role.
     */
    public function test_admin_can_update_user_role(): void
    {
        $this->assertEquals($this->analystRole->id, $this->analystUser->role_id);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.role', $this->analystUser->id), [
                'role_id' => $this->adminRole->id
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->analystUser->refresh();
        $this->assertEquals($this->adminRole->id, $this->analystUser->role_id);
    }

    /**
     * Admin can store and delete ports.
     */
    public function test_admin_can_store_and_delete_port(): void
    {
        // 1. Store Port
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.ports.store'), [
                'country_id' => $this->country->id,
                'port_code' => 'DEHAM',
                'name' => 'Port of Hamburg',
                'latitude' => 53.54,
                'longitude' => 9.92,
                'waiting_time_hours' => 12,
                'congestion_rate' => 35.5,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ports', [
            'port_code' => 'DEHAM',
            'name' => 'Port of Hamburg',
        ]);

        $port = Port::where('port_code', 'DEHAM')->first();

        // 2. Delete Port
        $deleteResponse = $this->actingAs($this->adminUser)
            ->delete(route('admin.ports.destroy', $port->id));

        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('ports', [
            'id' => $port->id,
        ]);
    }

    /**
     * Admin can store and delete articles.
     */
    public function test_admin_can_store_and_delete_article(): void
    {
        // 1. Store Article
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.articles.store'), [
                'title' => 'Global Shipping Bottlenecks',
                'content' => 'Geopolitical risks are disrupting trade paths across the canal.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'title' => 'Global Shipping Bottlenecks',
            'slug' => 'global-shipping-bottlenecks',
        ]);

        $article = Article::where('slug', 'global-shipping-bottlenecks')->first();

        // 2. Delete Article
        $deleteResponse = $this->actingAs($this->adminUser)
            ->delete(route('admin.articles.destroy', $article->id));

        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }
}
