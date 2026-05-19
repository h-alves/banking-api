<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function reset()
    {
        $this->accountService->reset();
        return response('OK', Response::HTTP_OK);
    }
}