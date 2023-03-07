<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeContentResource extends JsonResource
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
            'idGlobal' => $this->id_global,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'version' => [
                'major' => $this->version_major,
                'minor' => $this->version_minor,
            ],
            'apiUrl' => $this->api_url,
            'owner' => $this->owner,
            'body' => $this->body,
            'icon' => $this->icon,
            'activeFrom' => $this->active_from,
            'activeBefore' => $this->active_before,
            'basedType' => $this->based_type,
            'createdDate' => date('d.m.Y H:i:s', strtotime($this->created_date)),
            'createdAuthor' => $this->whenLoaded('createdAuthor', new UserTruncatedResource($this->createdAuthor)),
            'updatedDate' => date('d.m.Y H:i:s', strtotime($this->update_date)),
            'updatedAuthor' => $this->whenLoaded('updatedAuthor', new UserTruncatedResource($this->updatedAuthor)),
        ];
    }
}
