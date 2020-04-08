<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $model->id,
            'thumb' => $model->thumb,
            'name' => $model->name,
            'last_name' => $model->last_name,
            'email' => $model->email,
            'parent' => $model->parent,
            'pregnant' => $model->pregnant,
            'birth_date' => $model->birth_date,
            'info' => $model->info,
            'vk' => $model->vk,
            'instagram' => $model->instagram,
            'facebook' => $model->facebook,
            'children' => $model->relationLoaded('visibleChildren') ? $model->visibleChildren : [],
        ];
    }
}
