<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ClientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public $clientsService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ClientsService $service)
    {
        $this->clientsService = $service;
    }
    public function getListClients()
    {
        $result = $this->clientsService->getListClient($_GET);
        return $result;
    }
    public function create()
    {
        if (Auth::guard('web')->check()) {
            return view('clients.create');
        }
    }
}
