<?php

namespace App\Http\Resources;

use App\Models\Payments;
use App\Models\Tariffs;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'clientName'     => $this->name,
            'innClient'      => $this->inn,
            'status'         => 'Active',
            'tariff'         => Tariffs::find($this->tariff_id)->name,
            'tariffId'       => $this->tariff_id,
            'balance'        => (int)Payments::where(['user_id' => $this->id, 'up_down' => 'up'])->sum('amount') - (int)Payments::where(['user_id' => $this->id, 'up_down' => 'down'])->sum('amount'),
            'paymentState'   => $this->payment_state,
            'host'           => $this->host,
            'accessFrom'     => $this->access_from,
            'contract'       => $this->contract,
            'mail'           => $this->mail,
            'description'    => $this->description,
            'agent_fio'      => $this->agent_fio,
            'agent_position' => $this->agent_position,
            'agent_phone'    => $this->agent_phone,
            'agent_mail'     => $this->agent_mail,
            'createdDate'    => date('d.m.Y', strtotime($this->created_date)),
            'createdAuthor'  => $this->whenLoaded('createdAuthor', new UserTruncatedResource($this->createdAuthor)),
            'updatedDate'    => date('d.m.Y', strtotime($this->update_date)),
            'updatedAuthor'  => $this->whenLoaded('updatedAuthor', new UserTruncatedResource($this->updatedAuthor)),
        ];
    }
}
