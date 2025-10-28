<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\RequisicaoPeca;

class Requisicoe extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'data_requisicao',
        'status',
        'modelo',
        'voltagem',
        'descricao',
    ];

    public function pecas()
    {
        return $this->hasMany(RequisicaoPeca::class, 'requisicao_id');
    }
}
