<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoMaquinaToRequisicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Altera a tabela 'requisicoes'
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->integer('codigoMaquina')->nullable()->after('voltagem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->dropColumn('codigoMaquina');
        });
    }
}
