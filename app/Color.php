<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
        
    protected $table='color_tbl';

    protected $fillable = [
        'color_name',
        'color_image',
        'color_status',
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
        $data = Color::orderBy('id','desc')->get();
        return $data;
    }

    /*public function run()
    {
        Color::factory()
                ->times(50)
                ->hasPosts(10)
                ->create();
    }*/

}
