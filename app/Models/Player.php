<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Club;
use App\Models\Title;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'surnames',
        'birth_country',
        'country',
        'debut_season',
        'last_season',
        'photo',
    ];

    public function clubs() {
        return $this->belongsToMany(Club::class);
    }

    public function titles() {
        return $this->belongsToMany(Title::class);
    }
}
