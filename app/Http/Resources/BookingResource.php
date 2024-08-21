<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'flight_id' => $this->flight_id,
            'flight' => [
                'departure_airport' => $this->flight->departure_airport,
                'arrival_airport' => $this->flight->arrival_airport,
                'departure_time' => $this->flight->departure_time,
                'arrival_time' => $this->flight->arrival_time,
            ],
            'passenger_name' => $this->passenger_name,
            'passenger_email' => $this->passenger_email,
            'number_seats' => $this->number_seats,
            'total_cost' => $this->total_cost,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($request->user() && $request->user()->hasRole('admin')) {
            $data['credit_information'] = [
                'credit_card_number' => decrypt($this->credit_card_number),
                'credit_card_expiry_date' => decrypt($this->credit_card_expiry_date),
                'credit_card_cvv' => decrypt($this->credit_card_cvv),
            ];

            $data['log'] = $this->logs->map(function ($log) {
                // $log->changes
                $changeInfo = $log->changes;
                if (isset($changeInfo['credit_card_number'])) {
                    $changeInfo['credit_card_number'] = '...';
                }
                if (isset($changeInfo['credit_card_cvv'])) {
                    $changeInfo['credit_card_cvv'] = '...';
                }
                if (isset($changeInfo['credit_card_expiry_date'])) {
                    $changeInfo['credit_card_expiry_date'] = '...';
                }
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'changes' => $changeInfo,
                    'user_id' => $log->user_id,
                    'username' => $log->user_id ? $log->user->name :"", // Add this line to include the username for each log
                    'created_at' => $log->created_at,
                ];
            });
        }

        return $data;
    }
}
