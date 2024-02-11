<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $appends = [
        'phone',
        'location'
    ];
    protected $fillable = [
        'name',
        'jid',
        'zipcode',
        'public_place',
        'neighborhood',
        'city',
        'state',
        'number',
    ];


    public function getPhoneAttribute()
    {
        return explode('@', $this->jid)[0];
    }

    public function getLocationAttribute($number)
    {
        return 'CEP: '.$this->zipcode." \n ".
        'Logradouro: '.$this->public_place." \n ".
        'N° : '.$this->number." \n ".
        'Bairro: '.$this->neighborhood." \n ".
        'Cidade: '.$this->city." \n ".
        'Estado: '.$this->state." \n ";
    }
}
