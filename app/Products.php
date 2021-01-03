<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    
    protected $table='products_tbl';

    protected $fillable = [
        'product_name',
        'product_sku',
        'brand',
        'product_feature_image',
        'product_feature',
        'product_description',
        'product_status',
        'created_by',
        'updated_by'
    ];

    /**
     * get all content.
     *
     * @return mixed
     */
    public static function getAllContent()
    {
        $data = Stock::orderBy('id','desc')->get();
        return $data;
    }
}
