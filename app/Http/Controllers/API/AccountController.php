<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Gateways\ClientApiException;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccountController extends Controller
{

    /**
     * Returns user account balance
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ClientApiException If the user does not exist
     */
    public function balance(int $id): JsonResponse
    {
        try {
            $balance = (new AccountBalance($id))->balance();
            return response()->json(['value' => $balance], Response::HTTP_OK);
        } catch (ClientApiException $ex) {
            return response()->json($ex->report(), Response::HTTP_NOT_FOUND);
        }
    }
}
