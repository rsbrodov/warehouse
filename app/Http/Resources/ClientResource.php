<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'clientName' => $this->name,
            'innClient' => $this->inn,
            'status' => 'Active',
            'tariff' => $this->tariff_id,
            'paymentState' => $this->payment_state,
            'accessFrom' => $this->access_from,
            'contract' => $this->contract,
            'mail' => $this->mail,
            'description' => $this->description,
            'createdDate' => date('d.m.Y', strtotime($this->created_date)),
            'createdAuthor' => $this->whenLoaded('createdAuthor', new UserTruncatedResource($this->createdAuthor)),
            'updatedDate' => date('d.m.Y', strtotime($this->update_date)),
            'updatedAuthor' => $this->whenLoaded('updatedAuthor', new UserTruncatedResource($this->updatedAuthor)),
        ];
    }
}
