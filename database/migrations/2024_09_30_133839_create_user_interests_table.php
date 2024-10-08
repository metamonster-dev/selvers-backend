<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        
        Schema::create('user_interests', function (Blueprint $table) {
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->index()->constrained(table: 'categories')->onDelete('cascade');
        });

        // Insert Default Data
        $data = array(
            ['name' => '농축산/식음료'],
            ['name' => '에너지/환경'],
            ['name' => '섬유/의류'],
            ['name' => '금속/시계'],
            ['name' => '보건/의료'],
            ['name' => '건설/건축'],
            ['name' => '가정용품'],
            ['name' => '뷰티/화장품'],
            ['name' => '금융/부동산'],
            ['name' => '교육'],
            ['name' => '임신/출산/육아'],
            ['name' => '웨딩'],
            ['name' => '문화/예술'],
            ['name' => '레저/관광'],
        );
        foreach ($data as $datum)
            Category::create($datum);;

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interests');
        Schema::dropIfExists('categories');
    }
};
