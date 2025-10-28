<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisicaoPeca extends Model
{
    use HasFactory;

    public $timestamps = false; 

    protected $fillable = [
        'requisicao_id',
        'peca_id',
        'qtde',
    ];

    public function peca()
    {
        return $this->belongsTo(Peca::class, 'peca_id');
    }
}
