<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_domains', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('domain_id')->nullable();
            $table->integer('theme_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('full_domain')->nullable()->unique();
            $table->string('shop_name')->nullable()->unique();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable()->default('Active');
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
        Schema::dropIfExists('sub_domains');
    }
};
