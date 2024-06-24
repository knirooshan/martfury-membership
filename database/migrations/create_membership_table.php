<?php

use Botble\Membership\Enums\PlanStatusEnum;
use Botble\Membership\Enums\MembershipStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('membership_product_prices');

        Schema::create('membership_plans', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->foreignId('product_id')->nullable();
            $table->integer('price');
            $table->integer('duration');
            $table->string('status', 60)->default(PlanStatusEnum::ACTIVE);
            $table->timestamps();
        });

        Schema::create('membership_product_prices', function(Blueprint $table){
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('membership_plan_id');
            $table->integer('price');
            $table->timestamps();
        });

        Schema::create('memberships', function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('membership_plan_id');
            $table->string('status', 60)->default(MembershipStatusEnum::ACTIVE);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('membership_product_prices');
    }
};