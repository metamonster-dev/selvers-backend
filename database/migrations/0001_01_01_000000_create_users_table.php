<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth');
            $table->boolean('sex'); // False: 남성, True: 여성
            $table->string('contact')->default("");
            $table->string('email')->unique();
            $table->string('email_verity_token');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('password_updated_at')->default(now());
            $table->integer('state')->unsigned()->default(0); // 0: 인증 대기, 1: 일반 회원, 2: 탈퇴 회원
            $table->boolean('is_admin')->default(false); // False: 일반 회원, True: 관리자
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
