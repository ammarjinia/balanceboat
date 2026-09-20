<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centers extends Model {

    protected $table = "centers";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of centers
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objCenter = Centers::query();
        if (@$param['select']) {
            $objCenter = $objCenter->select($param['select']);
        }
        if (@$param['where']) {
            $objCenter = $objCenter->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenter = $objCenter->take($param['limit']);
        }
        $resCenter = $objCenter->orderBy($orderby, $order)->get();
        return $resCenter;
    }
    
    /**
     * Get the User record associated with the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Columns Centers::touchActivity() is allowed to write.
     * Whitelisted so a typo'd column name fails loudly instead of silently doing nothing.
     */
    public const ACTIVITY_COLUMNS = [
        'profile_updated_at',
        'availability_updated_at',
        'pricing_updated_at',
        'gallery_updated_at',
    ];

    /**
     * Record that a partner just changed one area of their listing.
     *
     * The email automation needs to know *what* was updated, not merely that the row was written:
     * `centers.updated_at` is DEFAULT current_timestamp and moves on unrelated writes, so it can't
     * distinguish "they refreshed their calendar" from "an import touched their meta description".
     * Each center-panel action that changes an area calls this with the matching column.
     *
     * Deliberately a bare query rather than a model save: this fires inside save handlers that
     * have already persisted their own changes, and it must not re-trigger their events or
     * overwrite anything else on the row.
     *
     * @param  int|string   $centerId
     * @param  string       $column  One of self::ACTIVITY_COLUMNS
     * @return void
     */
    public static function touchActivity($centerId, string $column)
    {
        if (!$centerId || !in_array($column, self::ACTIVITY_COLUMNS, true)) {
            return;
        }

        static::where('id', $centerId)->update([$column => now()]);
    }

}
