<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservadaToPecasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pecas', function (Blueprint $table) {
            $table->integer('qtde_reservada')->default(0)->after('qtde');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pecas', function (Blueprint $table) {
            $table->dropColumn('qtde_reservada');
        });
    }
}
