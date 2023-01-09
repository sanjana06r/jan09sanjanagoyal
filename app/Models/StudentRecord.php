<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class StudentRecord extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $fillable = [
        "student_id",
        "subject_id",
        "marks_scored",
        "grade"
    ];
    
    public function subjects()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function students()
    {
        return $this->belongsTo(Student::class);
    }
}
