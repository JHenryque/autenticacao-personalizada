<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // 1 {$ php artisan migrate } para enviar o modelo para mysql
    // 2 depois use o comando {$ php artisan make:seed UsersTableSeeder } para criar um usuario adm
    // 3 e opcional  {$ php artisan make:model User -f} para que o atributo senha e token seja protegindo
    // 4 {$ php artisan db:seed --class=UsersTableSeeder } para adcionar adm no mysql
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('password', 200);
            $table->string('token', 100)->nullable();
            $table->dateTime('email_verified_at')->nullable()->default(null);
            $table->dateTime('last_login_at')->nullable()->default(null);
            $table->boolean('active')->nullable()->default(null);
            $table->dateTime('blocked_until')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
