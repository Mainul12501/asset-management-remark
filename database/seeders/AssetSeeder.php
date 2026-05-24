<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssignAssetToStore;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = Store::query()
            ->whereBetween('id', [1, 106])
            ->orderBy('id')
            ->get(['id', 'title', 'code']);

        if ($stores->count() < 106) {
            throw new RuntimeException('AssetSeeder requires store ids 1 through 106.');
        }

        $assignedByUserId = User::query()->orderBy('id')->value('id');

        if (! $assignedByUserId) {
            throw new RuntimeException('AssetSeeder requires at least one user for assign_asset_to_stores logs.');
        }

        $assetTypes = $this->resolveAssetTypeIds();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            Asset::truncate();
            AssignAssetToStore::truncate();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $assets = [];
        $assignments = [];
        $assetCodeList = [];
        $baseDate = Carbon::create(2025, 1, 1, 9, 0, 0);

        foreach ($stores as $store) {
            $storeAssetCount = $store->id <= 18 ? 4 : 5;
            $plans = $this->buildStorePlan($store->id, $storeAssetCount);
            $storeCode = strtoupper((string) ($store->code ?: sprintf('S%03d', $store->id)));
            $storeLabel = trim((string) ($store->title ?: 'Store ' . $store->id));

            foreach ($plans as $slotIndex => $templateCode) {
                $template = $this->assetTemplates()[$templateCode];
                $slotNumber = $slotIndex + 1;
                $assetCode = sprintf('HRL-%s-%s-%02d', $storeCode, $templateCode, $slotNumber);
                $assetDate = $baseDate->copy()->addDays((($store->id - 1) * 3) + $slotIndex);

                $assets[] = [
                    'asset_type_id' => $assetTypes[$templateCode],
                    'name' => sprintf('%s - %s', $template['name'], $storeLabel),
                    'default_image' => null,
                    'store_id' => $store->id,
                    'asset_code' => $assetCode,
                    'has_kv_slot' => $template['has_kv_slot'],
                    'minimum_fee' => $template['minimum_fee'],
                    'asset_price' => $template['asset_price'],
                    'is_common_asset' => 0,
                    'planogram_pdf' => null,
                    'status' => 1,
                    'has_self' => $template['has_self'],
                    'total_self' => $template['total_self'],
                    'created_at' => $assetDate,
                    'updated_at' => $assetDate,
                ];

                $assetCodeList[] = $assetCode;

                $assignments[] = [
                    'asset_code' => $assetCode,
                    'store_id' => $store->id,
                    'assigned_by_user_id' => $assignedByUserId,
                    'assign_date' => $assetDate->toDateString(),
                    'asset_charge' => $template['asset_price'] > 0 ? $template['asset_price'] : $template['minimum_fee'],
                    'created_at' => $assetDate,
                    'updated_at' => $assetDate,
                ];
            }
        }

        Asset::query()->insert($assets);

        $assetIdMap = Asset::query()
            ->whereIn('asset_code', $assetCodeList)
            ->pluck('id', 'asset_code')
            ->all();

        $assignmentRows = [];

        foreach ($assignments as $assignment) {
            $assignmentRows[] = [
                'asset_id' => $assetIdMap[$assignment['asset_code']] ?? null,
                'store_id' => $assignment['store_id'],
                'assigned_by_user_id' => $assignment['assigned_by_user_id'],
                'assign_date' => $assignment['assign_date'],
                'asset_charge' => $assignment['asset_charge'],
                'created_at' => $assignment['created_at'],
                'updated_at' => $assignment['updated_at'],
            ];
        }

        AssignAssetToStore::query()->insert($assignmentRows);
    }

    protected function resolveAssetTypeIds(): array
    {
        $codes = array_keys($this->assetTemplates());

        $assetTypeIds = AssetType::query()
            ->whereIn('code', $codes)
            ->pluck('id', 'code')
            ->all();

        $missingCodes = array_values(array_diff($codes, array_keys($assetTypeIds)));

        if ($missingCodes !== []) {
            throw new RuntimeException('Missing asset type rows for codes: ' . implode(', ', $missingCodes));
        }

        return $assetTypeIds;
    }

    protected function buildStorePlan(int $storeId, int $assetCount): array
    {
        $fixtureCodes = [
            'LHC', 'ACG', 'NPG', 'CAC', 'SAT', 'FS1', 'FS2', 'FS3', 'CG3', 'HCG',
            'WG2', 'WG4', 'BO2', 'BO4', 'BL2', 'BL4', 'CG2', 'FDG', 'MMM', 'GBG', 'GWS',
        ];
        $staticCodes = ['BBD', 'BAN', 'LTB', 'WND', 'SHB', 'STD'];
        $digitalCodes = ['LED', 'LCD'];

        $plan = [
            $fixtureCodes[($storeId - 1) % count($fixtureCodes)],
            $fixtureCodes[$storeId % count($fixtureCodes)],
            $fixtureCodes[($storeId + 1) % count($fixtureCodes)],
            $staticCodes[($storeId - 1) % count($staticCodes)],
        ];

        if ($assetCount >= 5) {
            $plan[] = $digitalCodes[($storeId - 1) % count($digitalCodes)];
        }

        return $plan;
    }

    protected function assetTemplates(): array
    {
        return [
            'LHC' => [
                'name' => 'Low Height Cabinet',
                'minimum_fee' => 4500,
                'asset_price' => 4500,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 2,
            ],
            'ACG' => [
                'name' => 'Accessories Gondola',
                'minimum_fee' => 29000,
                'asset_price' => 29000,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'NPG' => [
                'name' => 'Nailpolish Gondola',
                'minimum_fee' => 18676,
                'asset_price' => 18676,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'CAC' => [
                'name' => 'Cash Counter',
                'minimum_fee' => 21475,
                'asset_price' => 21475,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 2,
            ],
            'SAT' => [
                'name' => 'Skin Analyzer Table',
                'minimum_fee' => 5735,
                'asset_price' => 5735,
                'has_kv_slot' => 0,
                'has_self' => 0,
                'total_self' => null,
            ],
            'FS1' => [
                'name' => 'FSU Type - 1',
                'minimum_fee' => 4107,
                'asset_price' => 4107,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 3,
            ],
            'FS2' => [
                'name' => 'FSU Type - 2',
                'minimum_fee' => 4247,
                'asset_price' => 4247,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'FS3' => [
                'name' => 'FSU Type - 3',
                'minimum_fee' => 8511,
                'asset_price' => 8511,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'CG3' => [
                'name' => 'Center Gondola-3',
                'minimum_fee' => 45064,
                'asset_price' => 45064,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'HCG' => [
                'name' => 'Home Care Gondola',
                'minimum_fee' => 5900,
                'asset_price' => 5900,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'WG2' => [
                'name' => '2 Feet Wall Gondola',
                'minimum_fee' => 15357,
                'asset_price' => 15357,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 5,
            ],
            'WG4' => [
                'name' => '4 Feet Wall Gondola',
                'minimum_fee' => 23957,
                'asset_price' => 23957,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 5,
            ],
            'BO2' => [
                'name' => '2 Feet Bothside Open Gondola',
                'minimum_fee' => 13146,
                'asset_price' => 13146,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'BO4' => [
                'name' => '4 Feet Bothside Open Gondola',
                'minimum_fee' => 16285,
                'asset_price' => 16285,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'BL2' => [
                'name' => '2 Feet Bothside Open Low Height Gondola',
                'minimum_fee' => 11584,
                'asset_price' => 11584,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 3,
            ],
            'BL4' => [
                'name' => '4 Feet Bothside Open Low Height Gondola',
                'minimum_fee' => 14537,
                'asset_price' => 14537,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 3,
            ],
            'CG2' => [
                'name' => 'Center Gondola-2 (SSCG)',
                'minimum_fee' => 22990,
                'asset_price' => 22990,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'FDG' => [
                'name' => 'Front Display Gondola',
                'minimum_fee' => 33742,
                'asset_price' => 33742,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'MMM' => [
                'name' => 'Makeup Mirror Module',
                'minimum_fee' => 18018,
                'asset_price' => 18018,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'GBG' => [
                'name' => 'Gift Box Gondola',
                'minimum_fee' => 7628,
                'asset_price' => 7628,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 3,
            ],
            'GWS' => [
                'name' => 'Gift Wrapping Stand',
                'minimum_fee' => 5855,
                'asset_price' => 5855,
                'has_kv_slot' => 0,
                'has_self' => 1,
                'total_self' => 2,
            ],
            'GON' => [
                'name' => 'Gondola',
                'minimum_fee' => 0,
                'asset_price' => 0,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 4,
            ],
            'ECD' => [
                'name' => 'End Cap Display',
                'minimum_fee' => 0,
                'asset_price' => 0,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 3,
            ],
            'CDU' => [
                'name' => 'Counter Display Unit',
                'minimum_fee' => 0,
                'asset_price' => 0,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 2,
            ],
            'WDR' => [
                'name' => 'Wall Display Rack',
                'minimum_fee' => 0,
                'asset_price' => 0,
                'has_kv_slot' => 1,
                'has_self' => 1,
                'total_self' => 5,
            ],
            'BBD' => [
                'name' => 'Billboard',
                'minimum_fee' => 18000,
                'asset_price' => 18000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'BAN' => [
                'name' => 'Banner',
                'minimum_fee' => 7000,
                'asset_price' => 7000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'LTB' => [
                'name' => 'Light Box',
                'minimum_fee' => 12000,
                'asset_price' => 12000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'WND' => [
                'name' => 'Window Display',
                'minimum_fee' => 10000,
                'asset_price' => 10000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'SHB' => [
                'name' => 'Shelf Branding Strip',
                'minimum_fee' => 3500,
                'asset_price' => 3500,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'STD' => [
                'name' => 'Standee',
                'minimum_fee' => 9000,
                'asset_price' => 9000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'LED' => [
                'name' => 'LED TV',
                'minimum_fee' => 30000,
                'asset_price' => 30000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
            'LCD' => [
                'name' => 'LCD TV',
                'minimum_fee' => 24000,
                'asset_price' => 24000,
                'has_kv_slot' => 1,
                'has_self' => 0,
                'total_self' => null,
            ],
        ];
    }
}
