<?php

namespace Database\Seeders;

use App\Models\VesselType;
use Illuminate\Database\Seeder;

class VesselTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'ACCOMODATION & WORK BARGE',
            'BARGE',
            'BULK CARRIER',
            'CHEMICAL BARGE',
            'CHEMICAL TANKER',
            'CONTAINER',
            'CONTAINER SHIP',
            'CREW BOAT',
            'GENERAL CARGO',
            'LANDING CRAFT',
            'LIFTBOAT',
            'OIL TANKER',
            'OSV',
            'PONTOON',
            'RORO PASSENGER',
            'SELF PROPELLED CARGO BARGE',
            'SELF PROPELLED CRANE BARGE',
            'TANKER',
            'TUG BOAT',
            'XTG',
            'FIBERGLASS CRUISER',
            'OIL BARGE',
            'SPOB',
            'YACHT',
        ];

        foreach ($types as $type) {
            VesselType::firstOrCreate(['name' => $type], ['type_desc' => 'Standard maritime classification']);
        }
    }
}
