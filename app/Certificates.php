<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificates extends Model {
    
    protected $table = "certificates";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];
    
    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of certificates
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ?: "name";
        $order = (@$param['order']) ?: "ASC";
		$objCertificates = Certificates::query();
        if (@$param['select']) {
            $objCertificates = $objCertificates->select($param['select']);
        }
        if (@$param['where']) {
            $objCertificates = $objCertificates->where($param['where']);
        }
		if (@$param['limit']) {
            $objCertificates = $objCertificates->take($param['limit']);
        }
        $resCertificates = $objCertificates->orderBy($orderby, $order)->get();
        return $resCertificates;
    }

}
