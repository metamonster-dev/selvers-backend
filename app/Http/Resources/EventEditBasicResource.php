<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Event;
use App\Models\EventSurvey;
use App\Models\EventFAQ;
use App\Models\EventBooth;
use App\Models\EventReject;

use App\Models\Category;
use App\Models\Tag;

class EventEditBasicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ret = [
            'title' => $this->title,
            'category' => $this->category,
            'img1' => $this->img1,
            'img2' => $this->img2,
            'event_start_date' => $this->event_start_date,
            'event_end_date' => $this->event_end_date,
            'event_start_time' => $this->event_start_time,
            'event_end_time' => $this->event_end_time,

            'payable_type' => $this->payable_type,
            'payable_start_date' => $this->payable_start_date,
            'payable_end_date' => $this->payable_end_date,
            'payable_price_url' => $this->payable_price_url,
            'payable_price1' => $this->payable_price1,
            'payable_price2' => $this->payable_price2,

            'title' => $this->title,
            'progress_type' => $this->progress_type,
            'progress_url' => $this->progress_url,
            'position1' => $this->position1,
            'position2' => $this->position2,

            'is_reject' => [
                'title' => $this->reject->title,
                'category' => $this->reject->category,
                'img' => $this->reject->img,
                'date' => $this->reject->date,
                'time' => $this->reject->time,
                'payable' => $this->reject->payable,
                'progress' => $this->reject->progress,
                'position' => $this->reject->position,
            ],
        ];

        return $ret;
    }
}
