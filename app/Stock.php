<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    
    protected $table='stock_tbl';

    protected $fillable = [
        'product_id',
        'color_id',
        'qr_code',
        'num_of_product',
        'num_of_sell',
        'buy_price',
        'demo_price',
        'sell_price',
        'product_special',
        'product_image',
        'stock_description',
        'stock_status',
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
