<?php

namespace App\Http\Controllers\Backend\Asset;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Store;
use App\Models\Thana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index()
    {
        return view('backend.asset-management.store-theme', [
            'stores'    => Store::with('storeManager', 'division', 'district', 'thana', 'storeLayouts')->latest()->get(),
            'users'     => User::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function getDistricts($divisionId)
    {
        $districts = District::select('id', 'name')
            ->where('division_id', $divisionId)
            ->orderBy('name')
            ->get();
        return response()->json($districts);
    }

    public function getThanas($districtId)
    {
        $thanas = Thana::select('id', 'name')
            ->where('district_id', $districtId)
            ->orderBy('name')
            ->get();
        return response()->json($thanas);
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules(), $this->validationMessages());

        try {
            DB::transaction(function () use ($request) {
                Store::updateOrCreateStore($request);
            });
            return response()->json(['success' => true, 'message' => 'Store created successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function show(string $id)
    {
        $store = Store::with('storeManager', 'storeLayouts', 'division', 'district', 'thana')->findOrFail($id);
        return response()->json($store);
    }

    public function edit(string $id)
    {
        $store = Store::findOrFail($id);
        return response()->json($store);
    }

    public function update(Request $request, string $id)
    {
        $store = Store::findOrFail($id);

        $rules = $this->validationRules($store->id);

        $request->validate($rules, $this->validationMessages());

        try {
            DB::transaction(function () use ($request, $store) {
                Store::updateOrCreateStore($request, $store);
            });
            return response()->json(['success' => true, 'message' => 'Store updated successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);
        $store->delete();
        return response()->json(['success' => true, 'message' => 'Store deleted successfully.']);
    }

    private function validationRules($ignoreId = null): array
    {
        return [
            'title'               => ['required', 'string', 'max:255', Rule::unique('stores', 'title')->ignore($ignoreId)],
            'code'                => ['required', 'string', 'min:2', 'max:3', 'alpha', Rule::unique('stores', 'code')->ignore($ignoreId)],
            'store_code'          => 'nullable|string|max:50',
            'total_area_sqft'     => 'nullable|numeric|min:0',
            'address'             => 'nullable|string|max:1000',
            'area'                => 'nullable|string|max:255',
            'division_id'         => 'nullable|exists:divisions,id',
            'district_id'         => 'nullable|exists:districts,id',
            'thana_id'            => 'nullable|exists:thanas,id',
            'postal_code'         => 'nullable|string|max:20',
            'latitude'            => 'nullable|numeric|between:-90,90',
            'longitude'           => 'nullable|numeric|between:-180,180',
            'monthly_rent'        => 'nullable|numeric|min:0',
            'per_sqr_feet_rent'   => 'nullable|numeric|min:0',
            'store_layout_pdf'    => 'nullable|mimes:pdf|max:10240',
            'contact_persion'     => 'nullable|string|max:255',
            'shop_official_mobile'=> 'nullable|string|max:20',
            'shop_official_email' => 'nullable|email|max:255',
            'status'              => 'required|in:0,1',
            'store_manager_id'    => 'nullable|exists:users,id',
            'opened_date'         => 'nullable|date',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'title.required' => 'The store title is required.',
            'title.unique'   => 'This store title is already taken.',
            'code.required'  => 'The store code is required.',
            'code.unique'    => 'This store code is already in use.',
            'latitude.between'  => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'store_layout_pdf.max' => 'The layout PDF must not exceed 10MB.',
            'shop_official_email.email' => 'Please enter a valid email address.',
        ];
    }
}
