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
        Schema::create('website_purchases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('domain_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('theme', 191)->nullable();

            $table->string('payment_method', 191)->nullable();
            $table->text('transaction_hash')->nullable();
            $table->string('status', 191)->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_purchases');
    }
};
