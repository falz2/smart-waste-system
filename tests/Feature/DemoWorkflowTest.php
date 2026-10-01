<?php

namespace Tests\Feature;

use App\Models\Bin;
use App\Models\Collection;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_resident_can_submit_a_report(): void
    {
        $user = User::factory()->create();
        $bin = $this->createBin('BIN-001', 85);

        $this->actingAs($user)
            ->get(route('reports.create'))
            ->assertOk()
            ->assertSee('New Report');

        $this->actingAs($user)
            ->post(route('reports.store'), [
                'type' => 'full_bin',
                'bin_id' => $bin->id,
                'description' => 'Overflowing for 3 days',
                'latitude' => '0.3476',
                'longitude' => '32.5825',
            ])
            ->assertRedirect(route('reports.index'))
            ->assertSessionHas('success', 'Report submitted successfully');

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'bin_id' => $bin->id,
            'type' => 'full_bin',
            'description' => 'Overflowing for 3 days',
            'status' => 'pending',
        ]);
    }

    public function test_public_sensor_reading_and_authenticated_simulation_update_bin_status(): void
    {
        $bin = $this->createBin('BIN-002', 45);

        $this->postJson(route('iot.update'), [
            'bin_code' => $bin->bin_code,
            'fill_level' => 85,
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('status', 'full');

        $this->assertDatabaseHas('bins', [
            'id' => $bin->id,
            'fill_level' => 85,
            'status' => 'full',
        ]);

        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('iot.simulate'))
            ->assertOk()
            ->assertSee('Live Bin Feed');

        $this->actingAs($user)
            ->postJson(route('iot.reset'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('bins', [
            'id' => $bin->id,
            'fill_level' => 0,
            'status' => 'empty',
        ]);
    }

    public function test_collection_assignment_and_completion_updates_bin_and_truck(): void
    {
        $user = User::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);
        $bin = $this->createBin('BIN-003', 85);
        $truck = Truck::create([
            'plate_number' => 'UBG 123A',
            'driver_name' => 'Demo Driver',
            'driver_phone' => '0780000000',
            'capacity' => 100,
            'status' => 'available',
        ]);

        $this->actingAs($user)
            ->get(route('collections.create'))
            ->assertOk()
            ->assertSee($bin->bin_code)
            ->assertSee($truck->plate_number);

        $this->actingAs($user)
            ->post(route('collections.store'), [
                'bin_id' => $bin->id,
                'truck_id' => $truck->id,
                'collector_id' => $collector->id,
            ])
            ->assertRedirect(route('collections.index'));

        $collection = Collection::query()->firstOrFail();
        $this->assertSame('on_route', $truck->fresh()->status);
        $this->actingAs($user)
            ->get(route('collections.show', $collection))
            ->assertOk()
            ->assertSee($bin->bin_code)
            ->assertSee($truck->plate_number)
            ->assertSee($collector->name);

        $this->actingAs($user)
            ->from(route('collections.show', $collection))
            ->patch(route('collections.complete', $collection))
            ->assertRedirect(route('collections.show', $collection));

        $this->assertDatabaseHas('collections', [
            'id' => $collection->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('bins', [
            'id' => $bin->id,
            'fill_level' => 0,
            'status' => 'empty',
        ]);
        $this->assertSame('available', $truck->fresh()->status);
    }

    private function createBin(string $code, int $fillLevel): Bin
    {
        return Bin::create([
            'bin_code' => $code,
            'location_name' => 'Kampala Road',
            'latitude' => 0.3136,
            'longitude' => 32.5811,
            'fill_level' => $fillLevel,
            'status' => $fillLevel >= 80 ? 'full' : ($fillLevel >= 50 ? 'partial' : 'empty'),
            'is_active' => true,
        ]);
    }
}
