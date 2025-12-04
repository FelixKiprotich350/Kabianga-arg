<?php

namespace Tests\Feature;

use App\Models\ResearchProject;
use App\Models\User;
use App\Models\Proposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundingAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_project_owner_can_request_funding()
    {
        // Create users
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        // Create a proposal and project
        $proposal = Proposal::factory()->create(['useridfk' => $owner->userid]);
        $project = ResearchProject::factory()->create([
            'proposalidfk' => $proposal->proposalid,
            'commissioningdate' => now()
        ]);
        
        // Test with non-owner user
        $this->actingAs($otherUser, 'api')
            ->postJson("/api/v1/projects/{$project->researchid}/funding", [
                'amount' => 1000
            ])
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Only the project owner can request funding'
            ]);
    }

    public function test_only_commissioned_projects_can_request_funding()
    {
        // Create user and project without commissioning date
        $owner = User::factory()->create();
        $proposal = Proposal::factory()->create(['useridfk' => $owner->userid]);
        $project = ResearchProject::factory()->create([
            'proposalidfk' => $proposal->proposalid,
            'commissioningdate' => null
        ]);
        
        // Test funding request on non-commissioned project
        $this->actingAs($owner, 'api')
            ->postJson("/api/v1/projects/{$project->researchid}/funding", [
                'amount' => 1000
            ])
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Project must be commissioned before funding can be requested'
            ]);
    }

    public function test_project_owner_can_request_funding_for_commissioned_project()
    {
        // Create user and commissioned project
        $owner = User::factory()->create();
        $proposal = Proposal::factory()->create(['useridfk' => $owner->userid]);
        $project = ResearchProject::factory()->create([
            'proposalidfk' => $proposal->proposalid,
            'commissioningdate' => now()
        ]);
        
        // Test successful funding request
        $this->actingAs($owner, 'api')
            ->postJson("/api/v1/projects/{$project->researchid}/funding", [
                'amount' => 1000
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Funding Request Submitted Successfully!!',
                'type' => 'success'
            ]);
    }
}