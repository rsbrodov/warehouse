<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentsRequest;
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
