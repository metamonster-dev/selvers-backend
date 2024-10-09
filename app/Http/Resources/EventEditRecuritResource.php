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

class EventEditRecuritResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ret = [
            'recurit_type' => $this->recurit_type,
            'recurit_start_date' => $this->recurit_start_date,
            'recurit_end_date' => $this->recurit_end_date,
            'recurit_start_time' => $this->recurit_start_time,
            'recurit_end_time' => $this->recurit_end_time,

            'informations' => Information::all()->map(function ($value) {
                $info = $value->events->find($this->id);
                return [
                    "id" => $value->id,
                    "name" => $value->name,
                    "is_set" => $info != null,
                    "can_required" => $value->can_required,
                    "required" => ($info != null) ? ($info->pivot->required == 1 ? true : false) : !$value->can_required,
                ];
            }),
            
            'is_reject' => [
                'recurit_date' => $this->reject->recurit_date,
                'recurit_type' => $this->reject->recurit_type,
                'recurit_information' => $this->reject->recurit_information,
            ],
        ];
        
        return $ret;
    }
}
