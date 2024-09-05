<?php

// app/Listeners/LogSuccessfulLogin.php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        // Giriş yapan kullanıcı bilgileri
        $user = $event->user;

        // Kullanıcı log kaydı oluştur
        DB::table('logs')->insert([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'login_at' => Carbon::now(),
        ]);
    }
}
