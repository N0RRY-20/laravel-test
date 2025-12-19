<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $guarded = ['id'];

    public function homeroomTeacher()
    {
        return $this->belongsTo(Employee::class, 'homeroom_teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
