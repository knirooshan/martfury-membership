<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateLegacyMembershipData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ec_products') || !Schema::hasTable('membership_product_prices')) {
            return;
        }

        // Check if legacy columns exist on ec_products
        $hasP1 = Schema::hasColumn('ec_products', 'member_price_1');
        $hasP2 = Schema::hasColumn('ec_products', 'member_price_2');
        $hasP3 = Schema::hasColumn('ec_products', 'member_price_3');
        $hasLimit = Schema::hasColumn('ec_products', 'product_limit');
        $hasDuration = Schema::hasColumn('ec_products', 'limit_duration');

        if (!$hasP1 && !$hasP2 && !$hasP3) {
            return;
        }

        $products = DB::table('ec_products')->get();

        foreach ($products as $product) {
            $limit = $hasLimit ? ($product->product_limit ?? null) : null;
            $duration = $hasDuration ? ($product->limit_duration ?? null) : null;

            // Plan 1 (Gold)
            if ($hasP1 && !empty($product->member_price_1) && (float) $product->member_price_1 > 0) {
                DB::table('membership_product_prices')->updateOrInsert(
                    ['product_id' => $product->id, 'plan_id' => 1],
                    [
                        'price'          => (float) $product->member_price_1,
                        'product_limit'  => $limit,
                        'limit_duration' => $duration,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]
                );
            }

            // Plan 2 (Platinum)
            if ($hasP2 && !empty($product->member_price_2) && (float) $product->member_price_2 > 0) {
                DB::table('membership_product_prices')->updateOrInsert(
                    ['product_id' => $product->id, 'plan_id' => 2],
                    [
                        'price'          => (float) $product->member_price_2,
                        'product_limit'  => $limit,
                        'limit_duration' => $duration,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]
                );
            }

            // Plan 3 (Diamond)
            if ($hasP3 && !empty($product->member_price_3) && (float) $product->member_price_3 > 0) {
                DB::table('membership_product_prices')->updateOrInsert(
                    ['product_id' => $product->id, 'plan_id' => 3],
                    [
                        'price'          => (float) $product->member_price_3,
                        'product_limit'  => $limit,
                        'limit_duration' => $duration,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Safe to leave migrated prices
    }
}
