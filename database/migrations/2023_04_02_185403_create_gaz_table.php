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
        Schema::create('gaz', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('weight', 8, 3);
            $table->string('chemical_formula', 40);
            $table->string('chemical_formula_html', 100);
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
        Schema::dropIfExists('gaz');
    }
};
