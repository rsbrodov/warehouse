<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DictionaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'archive' => (bool)$this->archive,
            'createdDate' => date('d.m.Y H:i:s', strtotime($this->created_date)),
            'createdAuthor' => $this->whenLoaded('createdAuthor', new UserTruncatedResource($this->createdAuthor)),
            'updatedDate' => date('d.m.Y H:i:s', strtotime($this->update_date)),
            'updatedAuthor' => $this->whenLoaded('updatedAuthor', new UserTruncatedResource($this->updatedAuthor)),
        ];
    }
}
