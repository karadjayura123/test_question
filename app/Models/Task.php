<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';

    protected $fillable = ['title', 'description','status','number','user_id','created_at', 'updated_at'];

    public static function insertData($data)
    {
        return self::create($data);
    }
    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'parent_id');
    }
}
