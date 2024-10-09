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
            'type' => $this->recurit_type,
            'start_date' => $this->recurit_start_date,
            'end_date' => $this->recurit_end_date,
            'start_time' => $this->recurit_start_time,
            'end_time' => $this->recurit_end_time,

            'informations' => Information::all()->map(function ($value) {
                $info = $value->events->find($this->id);
                return [
                    "id" => $value->id,
                    "name" => $value->name,
                    "is_set" => $info != null,
                    "can_required" => $value->can_required,
                    "required" => ($info != null) ? $info->pivot->required : $value->can_required,
                ];
            }),
        ];
        
        return $ret;
    }
}
