<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Subtask extends Model
{


    protected $table = 'subtask'; // Имя таблицы для хранения категорий

    protected $fillable = ['title', 'description', 'status', 'number', 'user_id', 'created_at', 'updated_at', 'parent_id',  'data'];

    public function childs()
    {
        return $this->hasMany(self::class, 'data', 'id');
    }

    public function parent()
    {
        return $this->hasOne(self::class);
    }
}
