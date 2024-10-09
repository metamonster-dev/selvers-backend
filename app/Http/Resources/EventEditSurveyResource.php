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
use App\Models\Information;

class EventEditSurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ret = [
            'is_survey' => $this->is_survey,
            'surveys' => $this->surveys->map(function ($value) {
                return [
                    'type' => $value->type,
                    'options' => json_decode($value->options),
                    'required' => $value->required,
                    'is_reject' => $value->is_reject ? true : false,
                ];
            }),
            
            'is_reject' => [
                'survey' => $this->reject->survey,
            ],
        ];
        
        return $ret;
    }
}
