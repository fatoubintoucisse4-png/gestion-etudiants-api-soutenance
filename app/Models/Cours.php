<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    // app/Models/Cours.php
protected $fillable = [
    'libelle',
    'professeur',
    'volume_horaire'
];

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class);
    }
}