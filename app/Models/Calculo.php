<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculo extends Model
{
    protected $table = 'calculo';
    protected $primaryKey = 'id';
    public $timestamps = false; // Se não tiver colunas de created_at e updated_at

    protected $fillable = [
        'calculoresumo_id',
        'id_colaborador',
        'valor'
    ];

    // Relacionamento com CalculoResumo
    public function calculoResumo()
    {
        return $this->belongsTo(CalculoResumo::class, 'calculoresumo_id');
    }
}
