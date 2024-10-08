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
        $recurit = $this->recurit;

        $ret = [
            'start_date' => $recurit->start_date,
            'end_date' => $recurit->end_date,
            'start_time' => $recurit->start_time,
            'end_time' => $recurit->end_time,
            'type' => $recurit->type,

            'informations' => Information::all()->map(function ($value) use($recurit) {
                $info = $value->recurits->find($recurit->id);
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
