<?php

/**
 * Created by PhpStorm.
 * User: trinhnv
 * Date: 28/12/2017
 * Time: 15:29
 */

namespace App\Models;

use App\Facades\AuthAdmin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    public $autoCreator = false;
    public $autoUpdater = false;
    public $autoRank = false;

    /**
     * @return string
     */
    public static function table()
    {
        return with(new static)->table;
    }

    /**
     * Insert each item as a row. Does not generate events.
     *
     * @param array $items
     *
     * @return bool
     */
    public static function insertAll(array $items)
    {
        $now = Carbon::now();

        $items = collect($items)->map(function (array $data) use ($now) {
            if (with(new static)->autoCreator && !isset($data['creator_id']) && AuthAdmin::check()) {
                $data['creator_id'] = AuthAdmin::id();
            }
            if (with(new static)->autoRank && !isset($data['rank'])) {
                $data['rank'] = with(new static)->max('rank') + 1;
            }
            return with(new static)->timestamps ? array_merge([
                with(new static)::CREATED_AT => $now,
                with(new static)::UPDATED_AT => $now,
            ], $data) : $data;
        })->all();

        return DB::table(static::table())->insert($items);
    }

    /**
     * Insert each item as a row. Does not generate events.
     *
     * @param array $conditions
     * @param bool  $isPermanently
     *
     * @return bool
     */
    public static function deleteAll($conditions, $isPermanently = false)
    {
        $model = new static;

        $models = $model::where($conditions);
        return $isPermanently ? $models->forceDelete() : $models->delete();
    }

    public function scopeSearch($query, $searchTerm = '')
    {
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', IS_ACTIVE);
    }

    public function scopeCreateBy($query, $userId = '')
    {
        return $query->where('creator_id', $userId);
    }

    public function scopeUpdateBy($query, $userId = '')
    {
        return $query->where('updater_id', $userId);
    }

    public function scopeByUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'creator_id', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updater_id', 'id');
    }
}
