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

class EventEditFAQResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ret = [
            'is_FAQ' => $this->is_FAQ,
            'faqs' => $this->faqs->map(function ($value) {
                return [
                    'question' => $value->question,
                    'answer' => $value->answer,
                ];
            }),
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_number' => $this->contact_number,
        ];
        
        return $ret;
    }
}
