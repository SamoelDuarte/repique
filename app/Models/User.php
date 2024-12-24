<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'pontuacao',
        'role',
        'active',
        'deleted',
        'area_id',
    ];

    /**
     * Os atributos que devem ser ocultados nos arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'deleted' => 'boolean',
    ];

    /**
     * Relacionamento: Um usuário pode pertencer a uma área.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Mutator para garantir que a senha seja sempre criptografada.
     *
     * @param string $value
     */
}
