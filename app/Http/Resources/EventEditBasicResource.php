<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Event;
use App\Models\EventPayable;
use App\Models\EventRecurit;
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
            'event_start_date' => $this->start_date,
            'event_end_date' => $this->end_date,
            'event_start_time' => $this->start_time,
            'event_end_time' => $this->end_time,

            'payable_type' => $this->payable_type,
            'payable_start_date' => $this->payable_start_date,
            'payable_end_date' => $this->payable_end_date,
            'payable_price' => $this->payable_price,
            'payable_price_url' => $this->payable_price_url,

            'title' => $this->title,
            'progress_type' => $this->progress_type,
            'progress_url' => $this->progress_url,
            'position1' => $this->position1,
            'position2' => $this->position2,
        ];

        return $ret;
    }
}
