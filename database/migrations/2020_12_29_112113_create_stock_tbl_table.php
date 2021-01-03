<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_tbl', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('color_id');
            $table->string('qr_code')->nullable();
            $table->integer('num_of_product');
            $table->integer('num_of_sell')->default(0);
            $table->double('buy_price')->nullable();
            $table->double('demo_price')->nullable();
            $table->double('sell_price');
            $table->string('product_special')->nullable();
            $table->string('product_image')->nullable();
            $table->text('stock_description')->nullable();
            $table->string('stock_status');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_tbl');
    }
}
