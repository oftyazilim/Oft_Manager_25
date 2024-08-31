<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mamul extends Model
{
  use HasFactory;

  protected $table = 'OFTT_01_MAMULLER'; // Tablo ismini belirtiyoruz

  protected $primaryKey = 'ID'; // Eğer birincil anahtar 'ID' değilse, bunu belirtmelisiniz.

  protected $fillable = [
    'KOD',
    'TANIM',
    'STGRPKOD',
    'MMLGRPKOD',
    'AKTIF',
    'DUZENLEYENID',
    'SINIF',
    'SONDRMTARIH',
    'OLUSTURMATARIH',
    'SILINDI',
    'SILENID',
    'SILINMETARIH',
  ];

  public $timestamps = false; // Eğer tablonuzda created_at, updated_at gibi timestamp alanları yoksa bunu false yapmalısınız.

}
