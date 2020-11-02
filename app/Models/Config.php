<?php
/**
 * ConfigModel class
 * Author: trinhnv
 * Date: 2020/11/02 14:12
 */

namespace App\Models;

class Config extends Model
{
    protected $table = 'config';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'lang',
        'email',
        'fax',
        'location',
        'call_us',
    ];

}
