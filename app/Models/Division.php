<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'division_id';

    public function employee(){
        return $this->hasMany(Employee::class, 'division_id', 'division_id');
    }

    public function manager(){
        return $this->hasMany(Manager::class, 'division_id', 'division_id');
    }
}
