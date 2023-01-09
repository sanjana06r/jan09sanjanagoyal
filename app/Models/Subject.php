<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Subject extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $fillable = [
        "name"
    ];

    public function studentrecords()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
