<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emir extends Model
{
    use HasFactory;


  protected $table = 'OFTT_01_EMIRLERIS'; // Tablo ismini belirtiyoruz

  protected $primaryKey = 'ID'; // Eğer birincil anahtar 'ID' değilse, bunu belirtmelisiniz.

  protected $fillable = [
    'ISTASYONID',
    'URUNID',
    'PLANLANANMIKTAR',
    'URETIMMIKTAR',
    'URETIMSIRA',
    'DURUM',
    'NOTLAR',
    'PROSESNOT',
    'KAYITTARIH',
    'DUZENTARIH',
    'OLUSTURANID',
    'DUZENLEYENID',
    'AKTIF',
    'SILINDI',
    'SILENID',
    'SILINMETARIH'
  ];

  public $timestamps = false; // Eğer tablonuzda created_at, updated_at gibi timestamp alanları yoksa bunu false yapmalısınız.

}
