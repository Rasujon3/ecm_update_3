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
        Schema::create('pixels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('domain_id')->nullable()->constrained('domains')->onDelete('cascade');
            $table->foreignId('sub_domain_id')->nullable()->constrained('sub_domains')->onDelete('cascade');
            $table->string('pixel_id', 50)->nullable();
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
        Schema::dropIfExists('pixels');
    }
};
