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
        'user_id',
        'send',
        'valor'
    ];

    // Relacionamento com CalculoResumo
    public function calculoResumo()
    {
        return $this->belongsTo(CalculoResumo::class, 'calculoresumo_id');
    }

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
