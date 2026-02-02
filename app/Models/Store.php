<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mainul\CustomHelperFunctions\Helpers\CustomHelper;

class Store extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'total_area_sqft',
        'address',
        'area',
        'thana',
        'district',
        'division',
        'postal_code',
        'latitude',
        'longitude',
        'monthly_rent',
        'store_layout_img',
        'store_layout_pdf',
        'contact_persion',
        'shop_official_mobile',
        'shop_official_email',
        'status',
        'store_manager_id',
        'opened_date',
    ];

    protected $searchableFields = ['*'];

    public function storeManager()
    {
        return $this->belongsTo(User::class, 'store_manager_id');
    }

    public function storeLayouts()
    {
        return $this->hasMany(StoreLayout::class);
    }

    public function activeLayout()
    {
        return $this->hasOne(StoreLayout::class)->where('is_currently_active', 1);
    }

    public static function updateOrCreateStore($request, $store = null)
    {
        $data = [
            'title'               => $request->title,
            'code'                => strtoupper($request->code),
            'total_area_sqft'     => $request->total_area_sqft,
            'address'             => $request->address,
            'area'                => $request->area,
            'thana'               => $request->thana,
            'district'            => $request->district,
            'division'            => $request->division,
            'postal_code'         => $request->postal_code,
            'latitude'            => $request->latitude,
            'longitude'           => $request->longitude,
            'monthly_rent'        => $request->monthly_rent,
            'contact_persion'     => $request->contact_persion,
            'shop_official_mobile'=> $request->shop_official_mobile,
            'shop_official_email' => $request->shop_official_email,
            'status'              => $request->status,
            'store_manager_id'    => $request->store_manager_id ?: null,
            'opened_date'         => $request->opened_date,
        ];

        if ($request->hasFile('store_layout_pdf')) {
            $pdfFile = $request->file('store_layout_pdf');
            $pdfName = 'layout-pdf-' . time() . '.' . $pdfFile->getClientOriginalExtension();
            $pdfPath = $pdfFile->storeAs('stores', $pdfName, 'public');
            $data['store_layout_pdf'] = 'storage/' . $pdfPath;
        }

        $storeRecord = static::updateOrCreate(['id' => $store?->id], $data);

        // Create a StoreLayout record whenever layout pdf is uploaded
        if ($request->hasFile('store_layout_pdf')) {
            // Deactivate previous layouts
            $storeRecord->storeLayouts()->update(['is_currently_active' => 0]);

            StoreLayout::create([
                'store_id'            => $storeRecord->id,
                'layout_pdf'          => $data['store_layout_pdf'],
                'changed_at'          => now()->toDateString(),
                'is_currently_active' => 1,
            ]);
        }

        return $storeRecord;
    }
}
