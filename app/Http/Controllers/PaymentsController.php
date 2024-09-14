<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentsRequest;
use App\Models\Payments;
use App\Services\PaymentsService;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public $paymentsService;

    public function __construct(PaymentsService $service)
    {
        $this->paymentsService = $service;
    }

    public function store(PaymentsRequest $request)
    {
        $result = $this->paymentsService->store($request);
        return $result;
    }

    public function storeGet()
    {
        $request = $_GET;
        $newPayments = Payments::create([
            'user_id' => $request['user_id'],
            'amount' => $request['amount'],
            'type' => $request['type'],
            'up_down' => $request['up_down'],
            'params' => $request['params'] ? $request['params'] : NULL,
            'date' => Date('Y-m-d H:i:s', strtotime($request['date'])),
            'created_at' => Date('Y-m-d H:i:s'),
        ]);
        return $newPayments;
    }


    public function destroy($id)
    {
        $result = $this->paymentsService->destroy($id);
        return $result;
    }

    public function findPayments(){
        $result = $this->paymentsService->findPayments($_GET);
        return $result;
    }
}
