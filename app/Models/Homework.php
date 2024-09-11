<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'title', 'description', 'due_date', 'status', 'submitted_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
