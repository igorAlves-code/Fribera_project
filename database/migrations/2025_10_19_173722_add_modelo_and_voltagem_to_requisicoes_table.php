<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModeloAndVoltagemToRequisicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->string('modelo', 50)->nullable()->after('status'); 
            $table->string('voltagem', 10)->nullable()->after('modelo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->dropColumn('voltagem');
            $table->dropColumn('modelo');
        });
    }
}
