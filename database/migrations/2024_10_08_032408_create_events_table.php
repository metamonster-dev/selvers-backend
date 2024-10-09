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

            $table->date('event_start_date')->nullable();
            $table->date('event_end_date')->nullable();
            $table->time('event_start_time')->nullable();
            $table->time('event_end_time')->nullable();

            $table->integer('payable_type')->unsigned()->default(0); // 0: 선택 필요, 1: 무료, 2: 사전신청 무료/현장구매 유료, 3: 사전신청 무료/기간설정 유료/현장구매 유료, 4: 사전신청 유료/현장구매 유료, 5: 유료
            $table->date('payable_start_date')->nullable();
            $table->date('payable_end_date')->nullable();
            $table->string('payable_price_url')->nullable();
            $table->string('payable_price1')->nullable(); // 유료 신청 기간 비용
            $table->string('payable_price2')->nullable(); // 현장 신청 비용

            $table->integer('progress_type')->unsigned()->default(0); // 0: 오프라인, 1: 온라인, 2: 하이브리드
            $table->string('progress_url')->nullable();
            $table->string('position1')->nullable();
            $table->string('position2')->nullable();
            $table->text('content')->nullable();

            $table->integer('recurit_type')->unsigned()->default(0); // 0: 개인, 1: 단체, 2: 개인/단체
            $table->date('recurit_start_date')->nullable();
            $table->date('recurit_end_date')->nullable();
            $table->time('recurit_start_time')->nullable();
            $table->time('recurit_end_time')->nullable();

            $table->boolean('is_survey')->default(true);
            $table->boolean('is_FAQ')->default(true);

            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('xlsx')->nullable();
            $table->integer('state')->unsigned()->default(0); // 0: 작성중, 1: 대기중, 2: 수정 필요, 3: 완료

            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('event_tags', function (Blueprint $table) {
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->index()->constrained()->onDelete('cascade');
        });

        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('can_required'); // 필수 설정 가능 유무
        });

        Schema::create('event_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('information_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('required'); // 이벤트의 해당 정보에 대한 필수 유무
        });

        Schema::create('event_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->integer('type')->unsigned()->default(0); // 0: 단일, 1: 다중, 2: 장문
            $table->string('options'); // 배열 형태로 저장
            $table->boolean('required'); // 필수 설정 유무
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
            [ 'name' => '이름', 'can_required' => false ],
            [ 'name' => '이메일', 'can_required' => false ],
            [ 'name' => '휴대전화 번호', 'can_required' => false ],
            [ 'name' => '소속 (회사/기관/학교명)', 'can_required' => true ],
            [ 'name' => '부서', 'can_required' => true ],
            [ 'name' => '직함', 'can_required' => true ],
            [ 'name' => '성별', 'can_required' => true ],
            [ 'name' => '나이', 'can_required' => true ],
            [ 'name' => '거주지역', 'can_required' => true ],
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
        Schema::dropIfExists('event_information');
        Schema::dropIfExists('information');
        Schema::dropIfExists('event_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('events');
    }
};
