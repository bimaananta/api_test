<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function division(){
        return $this->belongsTo(Division::class, 'division_id', 'division_id');
    }
    public function office(){
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

}
