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
        Schema::create('gaz_to_group', function (Blueprint $table) {
            $table->integer('gaz_id')->index('gaz_id');
            $table->integer('gaz_group_id')->index('gaz_group_id');
            $table->primary(['gaz_id', 'gaz_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gaz_to_group');
    }
};
