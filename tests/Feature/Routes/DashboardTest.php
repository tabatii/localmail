<?php

namespace Tests\Feature\Routes;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_the_dashboard_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('localmail.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(\Tabatii\LocalMail\Livewire\Sidebar::class);
    }
}
