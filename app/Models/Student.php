<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Student extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        "name",
        "email",
        "phone",
        "address",
        "city",
        "state",
        "country",
        "status"
    ];
    public function studentrecords()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
