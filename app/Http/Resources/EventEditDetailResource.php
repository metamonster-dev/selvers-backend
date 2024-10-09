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

class EventEditDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ret = [
            'content' => $this->content,
            'tags' => $this->tags->map(function ($value) {
                return $value->name;
            }),

            'is_reject' => [
                'content' => $this->reject->content,
                'tag' => $this->reject->tag,
            ],
        ];
        
        return $ret;
    }
}
