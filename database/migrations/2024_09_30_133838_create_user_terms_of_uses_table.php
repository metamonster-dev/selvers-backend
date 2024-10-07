<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TermsOfUse;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('terms_of_uses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('context');
            $table->boolean('require')->default(false);
            $table->timestamps();
        });

        Schema::create('user_terms_of_uses', function (Blueprint $table) {
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('terms_of_use_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('agree');
            $table->timestamps();
        });

        // Insert Default Data
        $data = array(
            [
                'title' => '약관 1',
                'context' => '약관 1 내용',
                'require' => true
            ],
            [
                'title' => '약관 2',
                'context' => '약관 2 내용',
                'require' => true
            ],
            [
                'title' => '약관 3',
                'context' => '약관 3 내용',
                'require' => false
            ],
        );
        foreach ($data as $datum)
            $termsOfUse = TermsOfUse::create($datum);;

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_terms_of_uses');
        Schema::dropIfExists('terms_of_uses');
    }
};
