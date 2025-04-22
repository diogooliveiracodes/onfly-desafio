<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRoles;
use App\Enums\TravelStatus;
use Laravel\Sanctum\Sanctum;
use App\Models\TravelRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => UserRoles::ADMIN->value]);
        Sanctum::actingAs($this->user);
    }

    public function test_index_returns_user_requests()
    {
        TravelRequest::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('travel-requests.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'travelRequests' => [['id', 'destination', 'departure_date', 'return_date', 'status']]
            ]);
    }

    public function test_store_creates_travel_request()
    {
        $data = [
            'requester_name' => 'João Silva',
            'destination' => 'Rio de Janeiro',
            'departure_date' => now()->addDays(5)->format('Y-m-d'),
            'return_date' => now()->addDays(10)->format('Y-m-d'),
        ];

        $response = $this->postJson(route('travel-requests.store'), $data);

        $response->assertOk()
            ->assertJsonFragment(['destination' => 'Rio de Janeiro']);

        $this->assertDatabaseHas('travel_requests', [
            'user_id' => $this->user->id,
            'destination' => 'Rio de Janeiro',
        ]);
    }

    public function test_show_displays_specific_travel_request()
    {
        $travel = TravelRequest::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('travel-requests.show', $travel));

        $response->assertOk()
            ->assertJsonFragment(['id' => $travel->id]);
    }

    public function test_update_modifies_travel_request()
    {
        $travel = TravelRequest::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'requester_name' => 'Nome Atualizado',
            'destination' => 'São Paulo',
            'departure_date' => now()->addDays(2)->format('Y-m-d'),
            'return_date' => now()->addDays(4)->format('Y-m-d'),
        ];

        $response = $this->putJson(route('travel-requests.update', $travel), $data);

        $response->assertOk()
            ->assertJsonFragment(['destination' => 'São Paulo']);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travel->id,
            'destination' => 'São Paulo',
        ]);
    }

    public function test_change_status_updates_request_status()
    {
        $travel = TravelRequest::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson(route('travel-requests.changeStatus', $travel), [
            'status' => TravelStatus::APROVADO->value,
        ]);

        $response->assertOk()
            ->assertJsonFragment(['status' => 2]);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travel->id,
            'status' => TravelStatus::APROVADO->value,
        ]);
    }

    public function test_search_returns_filtered_results()
    {
        TravelRequest::factory()->create([
            'user_id' => $this->user->id,
            'destination' => 'Curitiba',
        ]);

        $response = $this->postJson(route('travel-requests.search'), [
            'destination' => 'Curitiba'
        ]);

        $response->assertOk()
            ->assertJsonCount(1, 'travelRequests');
    }
}
