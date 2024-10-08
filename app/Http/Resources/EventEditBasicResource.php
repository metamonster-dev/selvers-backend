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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'title' => $this->title,
            'progress_type' => $this->progress_type,
            'progress_url' => $this->progress_url,
            'position1' => $this->position1,
            'position2' => $this->position2,
        ];

        $payable = $this->payable;
        $ret['payable'] = [
            'type' => $payable->type,
            'start_date' => $payable->start_date,
            'end_date' => $payable->end_date,
            'price' => $payable->price,
            'price_url' => $payable->price_url,
        ];

        return $ret;
    }
}
