<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ElementContentShortResource extends JsonResource
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
            'label' => $this->label,
            'description' => $this->description,
            'apiUrl' => $this->api_url,
            'typeContent' => $this->type_content_id,
            'status' => $this->status,
            'version' => [
                'major' => $this->version_major,
                'minor' => $this->version_minor,
            ],
            'activeFrom' => $this->active_from,
            'activeBefore' => $this->active_before,
            'basedElement' => $this->based_element,
            'createdDate' => date('d.m.Y H:i:s', strtotime($this->created_at)),
            'createdAuthor' => $this->whenLoaded('createdAuthor', new UserTruncatedResource($this->createdAuthor)),
            'updatedDate' => date('d.m.Y H:i:s', strtotime($this->updated_at)),
            'updatedAuthor' => $this->whenLoaded('updatedAuthor', new UserTruncatedResource($this->updatedAuthor)),
        ];
    }
}
