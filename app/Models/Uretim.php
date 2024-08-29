<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uretim extends Model
{
    use HasFactory;
    protected $table = 'RAPOR_URETIM';
    protected $connection = 'sqlAkyazi';
  
    // protected $guarded = [];
  
  
}
