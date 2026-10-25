<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('plans')) {
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->unsignedBigInteger('product_id')->default(0);
                $table->decimal('amount', 15, 2)->default(0);
                $table->bigInteger('duration')->default(365);
                $table->integer('duration_value')->default(1);
                $table->string('duration_unit', 30)->default('year');
                $table->decimal('shipping_discount_percentage', 5, 2)->default(0);
                $table->decimal('global_discount_percentage', 5, 2)->default(0);
                $table->boolean('allow_coupon_combination')->default(true);
                $table->string('badge_color', 30)->nullable();
                $table->integer('order')->default(0);
                $table->text('description')->nullable();
                $table->string('status', 60)->default('active');
                $table->timestamps();
            });
        } else {
            Schema::table('plans', function (Blueprint $table) {
                if (!Schema::hasColumn('plans', 'slug')) {
                    $table->string('slug')->nullable()->after('name');
                }
                if (!Schema::hasColumn('plans', 'duration_value')) {
                    $table->integer('duration_value')->default(1)->after('duration');
                }
                if (!Schema::hasColumn('plans', 'duration_unit')) {
                    $table->string('duration_unit', 30)->default('year')->after('duration_value');
                }
                if (!Schema::hasColumn('plans', 'shipping_discount_percentage')) {
                    $table->decimal('shipping_discount_percentage', 5, 2)->default(0)->after('duration_unit');
                }
                if (!Schema::hasColumn('plans', 'global_discount_percentage')) {
                    $table->decimal('global_discount_percentage', 5, 2)->default(0)->after('shipping_discount_percentage');
                }
                if (!Schema::hasColumn('plans', 'allow_coupon_combination')) {
                    $table->boolean('allow_coupon_combination')->default(true)->after('global_discount_percentage');
                }
                if (!Schema::hasColumn('plans', 'badge_color')) {
                    $table->string('badge_color', 30)->nullable()->after('allow_coupon_combination');
                }
                if (!Schema::hasColumn('plans', 'order')) {
                    $table->integer('order')->default(0)->after('badge_color');
                }
                if (!Schema::hasColumn('plans', 'description')) {
                    $table->text('description')->nullable()->after('order');
                }
            });
        }

        if (!Schema::hasTable('memberships')) {
            Schema::create('memberships', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id');
                $table->unsignedBigInteger('user_id');
                $table->date('start_date');
                $table->date('end_date');
                $table->boolean('auto_renew')->default(false)->nullable();
                $table->string('status', 60)->default('active');
                $table->decimal('savings', 15, 2)->default(0)->nullable();
                $table->string('cus_name')->nullable();
                $table->text('name')->nullable();
                $table->date('last_reminder_sent_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index('end_date');
            });
        } else {
            Schema::table('memberships', function (Blueprint $table) {
                if (!Schema::hasColumn('memberships', 'last_reminder_sent_at')) {
                    $table->date('last_reminder_sent_at')->nullable()->after('name');
                }
            });
        }

        if (!Schema::hasTable('membership_histories')) {
            Schema::create('membership_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('membership_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('plan_id')->nullable();
                $table->string('action', 60);
                $table->text('description')->nullable();
                $table->decimal('savings_amount', 15, 2)->default(0)->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->timestamps();

                $table->index(['membership_id', 'action']);
                $table->index('user_id');
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
        Schema::dropIfExists('membership_histories');
        // Plans and Memberships tables preserved for data safety
    }
}
