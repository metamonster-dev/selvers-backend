<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Information;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->foreignId('category_id')->index()->constrained(table: 'categories')->onDelete('cascade');
            $table->string('img1')->nullable();
            $table->string('img2')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('progress_type')->unsigned()->default(0); // 0: 오프라인, 1: 온라인, 2: 하이브리드
            $table->string('progress_url')->nullable();
            $table->string('position1')->nullable();
            $table->string('position2')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_survey')->default(true);
            $table->boolean('is_FAQ')->default(true);

            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('xlsx')->nullable();
            $table->integer('state')->unsigned()->default(0); // 0: 작성중, 1: 대기중, 2: 수정 필요, 3: 완료

            $table->timestamps();
        });

        Schema::create('event_payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->integer('type')->unsigned()->default(0); // 비용 설정 0~4
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('price')->nullable();
            $table->string('price_url')->nullable();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('event_tags', function (Blueprint $table) {
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->index()->constrained()->onDelete('cascade');
        });

        Schema::create('event_recurits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->integer('type')->unsigned()->default(0); // 0: 개인, 1: 단체, 2: 개인/단체
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });

        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('require'); // 필수 설정 유무
        });

        Schema::create('event_recruit_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('information_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('require'); // 이벤트의 해당 정보에 대한 필수 유무
        });

        Schema::create('event_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->integer('type')->unsigned()->default(0); // 0: 단일, 1: 다중, 2: 장문
            $table->string('options'); // JSON 형태로 저장
            $table->boolean('require'); // 필수 설정 유무
            $table->boolean('is_reject')->default(false); // 수정 요청
        });

        Schema::create('event_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->string('question');
            $table->string('answer');
            $table->boolean('is_reject')->default(false); // 수정 요청
        });

        Schema::create('event_booths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->string('number');
            $table->string('name');
            $table->string('position');
            $table->string('url');
        });

        Schema::create('event_rejects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('title')->default(false);
            $table->boolean('category')->default(false);
            $table->boolean('img')->default(false);
            $table->boolean('date')->default(false);
            $table->boolean('time')->default(false);
            $table->boolean('payable')->default(false);
            $table->boolean('progress')->default(false);
            $table->boolean('position')->default(false);
            $table->boolean('content')->default(false);
            $table->boolean('tag')->default(false);
            $table->boolean('survey')->default(false);
            $table->boolean('faq')->default(false);
            $table->boolean('contact')->default(false);
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        $data = array(
            [ 'name' => '이름', 'require' => true ],
            [ 'name' => '이메일', 'require' => true ],
            [ 'name' => '휴대전화 번호', 'require' => true ],
            [ 'name' => '소속 (회사/기관/학교명)', 'require' => false ],
            [ 'name' => '부서', 'require' => false ],
            [ 'name' => '직함', 'require' => false ],
            [ 'name' => '성별', 'require' => false ],
            [ 'name' => '나이', 'require' => false ],
            [ 'name' => '거주지역', 'require' => false ],
        );
        foreach ($data as $datum)
            Information::create($datum);;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rejects');
        Schema::dropIfExists('event_booths');
        Schema::dropIfExists('event_faqs');
        Schema::dropIfExists('event_surveys');
        Schema::dropIfExists('event_recruit_information');
        Schema::dropIfExists('information');
        Schema::dropIfExists('event_recurits');
        Schema::dropIfExists('event_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('event_payables');
        Schema::dropIfExists('events');
    }
};
