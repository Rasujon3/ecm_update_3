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
        Schema::table('take_a_look_imgs', function (Blueprint $table) {
            $table->dropColumn('take_a_look_id');
            $table->unsignedBigInteger('domain_id')->nullable()->after('id');
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('take_a_look_img', function (Blueprint $table) {
            //
        });
    }
};
