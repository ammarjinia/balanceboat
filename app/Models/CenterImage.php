<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenterImage extends Model
{
    protected $table = 'center_image_gallery';

    protected $fillable = [
        'center_id',
        'image_title',
        'image_url',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
