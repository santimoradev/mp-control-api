<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'route_title' => $this->route?->title,
            'status' => $this->status,
            'location' => [
              'id' => $this->location?->id,
              'name' => $this->location?->name,
              'province_name' => $this->location?->province?->name,
              'city_name' => $this->location?->city?->name,
              'lat' => $this->location?->lat,
              'lng' => $this->location?->lng,
            ],
            'assigned_to' => [
              'id' => $this->assignedTo?->id,
              'first_name' => $this->assignedTo?->first_name,
              'last_name' => $this->assignedTo?->last_name,
            ],
              // 👇 Aquí aplanas
            'scheduled_date' => $this->scheduled_date,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'media' => $this->media ? [
                'name' => $this->media->file_name,
                'url' => $this->media->url,
            ] : null,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'business' => [
              'id' => $this->route?->business?->id,
              'name' => $this->route?->business?->name,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
