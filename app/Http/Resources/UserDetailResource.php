<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\UserCompany;
use App\Models\UserInterest;
use App\Models\Interest;

use App\Http\Resources\UserBasicResource;

class UserDetailResource extends UserBasicResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $company = $this->company;
        $ret = parent::toArray($request);
        $ret = array_merge($ret, [
            "password_updated_at" => $this->password_updated_at,
            "created_at" => $this->created_at,
            "state" => $this->state, // 0: 인증 대기, 1: 일반 회원, 2: 탈퇴 회원
            "deleted_at" => $this->deleted_at, // 0: 인증 대기, 1: 일반 회원, 2: 탈퇴 회원
            "terms_of_uses" => $this->termsOfUses->map(function ($value) {
                return [
                    "id" => $value->id,
                    "title" => $value->title,
                    "require" => $value->require,
                    "agree" => $value->pivot->agree ? true : false,
                    "updated_at" => $value->pivot->updated_at,
                ];
            }),
        ]);

        if ($company != null) {
            $ret["company"] = array_merge($ret["company"],[
                "accept" => $company->accept,
                "accepted_at" => $company->accepted_at,
                "company_id_file" => $company->company_id_file,
                "company_id_file_name" => $company->company_id_file_name,
            ]);
        }
        
        return $ret;
    }
}
