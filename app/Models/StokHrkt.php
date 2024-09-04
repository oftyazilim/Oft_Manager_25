<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokHrkt extends Model
{
    use HasFactory;


  protected $table = 'OFTT_01_STOKHRKT'; // Tablo ismini belirtiyoruz

  protected $primaryKey = 'ID'; // Eğer birincil anahtar 'ID' değilse, bunu belirtmelisiniz.

  protected $fillable = [
    'TUR',
    'STOKID',
    'ISEMRIID',
    'MIKTAR',
    'BIRIM',
    'ISTASYONID',
    'KAYITTARIH',
    'DUZENTARIH',
    'OLUSTURANID',
    'DUZENLEYENID',
    'SILINDI',
    'SILENID',
    'SILINMETARIH',
    'NOTLAR',
    'URETIMTARIH'
  ];

  public $timestamps = false; // Eğer tablonuzda created_at, updated_at gibi timestamp alanları yoksa bunu false yapmalısınız.

}
