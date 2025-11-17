<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class BomProcessTeam extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = Faker::create();

        // Get all BOMs with product_id
        $boms = DB::table('b_o_m_parts')->select('id', 'product_id')->get();

        // Get all available teams
        $teamIds = DB::table('process_teams')->pluck('id')->toArray();

        $processes = [];

        foreach ($boms as $bom) {
            $productId = $bom->product_id;
            $bomId = $bom->id;

            // Assign 1–3 stages for each BOM
            $stageCount = $faker->numberBetween(1, 3);

            // Shuffle team IDs to ensure uniqueness
            $availableTeams = $teamIds;
            shuffle($availableTeams);

            for ($stage = 1; $stage <= $stageCount; $stage++) {
                // Pick a unique team for this stage
                $teamId = array_pop($availableTeams);
                // If we run out of teams, just pick randomly (rare case)
                if (!$teamId) {
                    $teamId = $faker->randomElement($teamIds);
                }

                $processes[] = [
                    'product_id' => $productId,
                    'bom_id'     => $bomId,
                    'stage'      => "stage_".$stage,
                    'team_id'    => $teamId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('bom_process_teams')->insert($processes);
    }
}
