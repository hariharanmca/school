<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'grade', 'section',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
