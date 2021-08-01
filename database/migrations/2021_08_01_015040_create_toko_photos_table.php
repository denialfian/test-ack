<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokoPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toko_photos', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->tinyInteger('is_main')->default(0);
            $table->timestamps();

            $table->bigInteger('toko_id')->unsigned()->index();
            $table->foreign('toko_id')
                ->references('id')
                ->on('tokos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toko_photos');
    }
}
