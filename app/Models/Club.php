<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

class Club extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'badge'];

    public function players() {
        return $this->belongsToMany(Player::class);
    }
}
