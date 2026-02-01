<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Mainul\CustomHelperFunctions\Helpers\CustomHelper;

class Brand extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'description', 'status', 'logo'];

    protected $searchableFields = ['*'];

    public static function updateOrCreateBrand($request, $brand = null)
    {
        return static::updateOrCreate(['id' => $brand?->id], [
            'name'  => $request->name,
            'code'  => strtoupper($request['code']),
            'description'   => $request->description,
            'status'    => $request->status,
            'logo'  => CustomHelper::fileUpload($request->file('logo'), 'brands', 'brand-logo', 300, 300, $brand->logo ?? null),
        ]);
    }



}
