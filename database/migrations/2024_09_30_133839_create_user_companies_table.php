<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('company_id');
            $table->string('company_id_file');
            $table->string('name');
            $table->string('department');
            $table->string('position');
            $table->string('contact');
            $table->integer('accept')->unsigned()->nullable()->default(0); // 0: 대기, 1: 미승인, 2: 승인
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_companies');
    }
};
