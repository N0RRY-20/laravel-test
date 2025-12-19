<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(StudentParent::class, 'parent_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
