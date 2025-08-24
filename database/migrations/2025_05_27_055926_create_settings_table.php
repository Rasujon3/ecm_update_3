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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('app_name')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('courier_api_key')->unique()->nullable();
            $table->string('courier_secret')->unique()->nullable();
            $table->string('facebook_pixel_id')->unique()->nullable();
            $table->string('pathao_client_id')->nullable();
            $table->string('pathao_client_secret')->nullable();
            $table->text('pathao_access_token')->nullable();
            $table->text('order_note')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('contact_name')->nullable();
            $table->text('contact_phone')->nullable();
            $table->text('contact_email')->nullable();
            $table->text('contact_description')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('about_us')->nullable();
            $table->string('delivery_charge')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('refund_policy')->nullable();
            $table->string('inside_delivery_charge')->nullable();
            $table->string('outside_delivery_charge')->nullable();     
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
        Schema::dropIfExists('settings');
    }
};
