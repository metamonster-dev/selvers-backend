<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\UserCompany;
use App\Models\UserInterest;
use App\Models\Interest;

class UserBasicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $company = $this->company;

        $ret = [
            "user_id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "contact" => $this->contact,
            "sex" => $this->sex,
            "birth" => $this->birth,
            "interests" => $this->interests->map(function ($value) {
                return $value->id;
            }),
        ];

        if ($company != null) {
            $ret["company"] = [
                "company_name" => $company->company_name,
                "company_id" => $company->company_id,
                "name" => $company->name,
                "department" => $company->department,
                "position" => $company->position,
                "contact" => $company->contact,
            ];
        }
        
        return $ret;
    }
}
