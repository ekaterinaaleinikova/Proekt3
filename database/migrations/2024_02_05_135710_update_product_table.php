<?php

// database/migrations/2024_02_05_change_price_type_in_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductTable extends Migration
{
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->decimal('price', 8)->change();
        });
    }

    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->integer('price')->change();
        });
    }
}
