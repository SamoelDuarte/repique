<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculoResumo extends Model
{
    protected $table = 'calculo_resumo';
    protected $primaryKey = 'id';
    public $timestamps = false; // Se não tiver colunas de created_at e updated_at

    protected $fillable = [
        'total_gorjeta',
        'desconto',
        'restante',
        'total_salao',
        'total_ponto_salao',
        'cada_ponto_salao',
        'total_retaguarda',
        'total_ponto_retaguarda',
        'cada_ponto_retaguarda',
        'data'
    ];
    protected $dates = ['data'];
    

    public function calculos()
    {
        return $this->hasMany(Calculo::class, 'calculoresumo_id');
    }


    public function getDataAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }

    
}
