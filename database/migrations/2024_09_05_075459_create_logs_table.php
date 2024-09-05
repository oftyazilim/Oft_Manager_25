<?php
// database/migrations/xxxx_xx_xx_create_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Giriş yapan kullanıcının ID'si
            $table->string('ip_address');          // Kullanıcının IP adresi
            $table->timestamp('login_at');         // Giriş tarihi ve saati
            $table->timestamps();

            // Yabancı anahtar ilişkisi
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
