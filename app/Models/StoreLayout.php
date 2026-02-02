<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLayout extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'layout_img',
        'layout_pdf',
        'changed_at',
        'is_currently_active',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'store_layouts';

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
