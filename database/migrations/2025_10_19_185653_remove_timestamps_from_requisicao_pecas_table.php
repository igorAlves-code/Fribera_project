<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTimestampsFromRequisicaoPecasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisicao_pecas', function (Blueprint $table) {
            if (Schema::hasColumn('requisicao_pecas', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('requisicao_pecas', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('requisicao_pecas', 'pecas')) {
                $table->dropColumn('pecas');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisicao_pecas', function (Blueprint $table) {
            //
        });
    }
}
