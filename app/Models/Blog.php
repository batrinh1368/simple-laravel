<?php
/**
 * BlogModel class
 * Author: trinhnv
 * Date: 2020/11/02 15:06
 */

namespace App\Models;

use App\Traits\Models\FillableFields;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes, FillableFields;

    protected $table = 'blogs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'lang',
        'name',
        'slug_name',
        'image_url',
        'content',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
