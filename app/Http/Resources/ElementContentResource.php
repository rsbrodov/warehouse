<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ElementContentResource extends JsonResource
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
            'typeContent' => new TypeContentResource($this->typeContent),
            'status' => $this->status,
            'version' => [
                'major' => $this->version_major,
                'minor' => $this->version_minor,
            ],
            'body' => $this->body,
            'activeFrom' => $this->active_from ? date('d.m.Y', strtotime($this->active_from)) : null,
            'activeAfter' => $this->active_after ? date('d.m.Y', strtotime($this->active_after)) : null,
            'basedElement' => $this->based_element,
            'createdDate' => date('d.m.Y', strtotime($this->created_date)),
            'createdAuthor' => $this->whenLoaded('createdAuthor', new UserTruncatedResource($this->createdAuthor)),
            'updatedDate' => date('d.m.Y', strtotime($this->update_date)),
            'updatedAuthor' => $this->whenLoaded('updatedAuthor', new UserTruncatedResource($this->updatedAuthor)),
        ];
    }
}
