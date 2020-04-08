<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $model = $this->resource;
        return [
            'name' => $model['name'],
            'last_name' => $model['last_name'],
            'email_verified_at' => Carbon::make($model['email_verified_at'])->format('Y/m/d'),
            'promocode_created_at' => Carbon::make($model['promocode_created_at'])->format('Y/m/d H:i:s'),
            'promocode' => $model['promocode'],
            'discount' => $model['discount'],
        ];
    }
}
