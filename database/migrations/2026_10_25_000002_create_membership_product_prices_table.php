<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('membership_product_prices')) {
            Schema::create('membership_product_prices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('plan_id');
                $table->decimal('price', 15, 2)->default(0);
                $table->integer('product_limit')->nullable();
                $table->integer('limit_duration')->nullable();
                $table->timestamps();

                $table->unique(['product_id', 'plan_id'], 'prod_plan_unique');
                $table->index('product_id');
                $table->index('plan_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_product_prices');
    }
}
