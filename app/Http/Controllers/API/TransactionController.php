<?php

namespace App\Http\Controllers\API;

use App\Entities\Transactions\TransactionType;
use App\Exceptions\Gateways\AuthorizathorException;
use App\Exceptions\Gateways\ClientApiException;
use App\Exceptions\Transactions\TransferException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Services\Transactions\TransactionFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TransactionController extends Controller
{

    /**
     * Transfer money from one user to another user
     *
     * @param  App\Http\Requests\TransferRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ClientApiException If the user does not exist
     * @throws AuthorizathorException If you are not authorized to execute the transaction
     * @throws TransferException If the payer is a shopkeeper
     * @throws TransferException If the payer does not have enough balance
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $transaction = TransactionFactory::getTransactionType(TransactionType::transfer());
            $transaction->transact($data);
            return response()->json(["message" => $transaction->getMessage()], Response::HTTP_OK);
        } catch (ClientApiException | AuthorizathorException | TransferException $ex) {
            return response()->json($ex->report(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Deposit money into the user's account
     *
     * @param  App\Http\Requests\TransferRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ClientApiException If the user does not exist
     * @throws AuthorizathorException If you are not authorized to execute the transaction
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $transaction = TransactionFactory::getTransactionType(TransactionType::deposit());
            $transaction->transact($data);
            return response()->json(["message" => $transaction->getMessage()], Response::HTTP_OK);
        } catch (ClientApiException | AuthorizathorException $ex) {
            return response()->json($ex->report(), Response::HTTP_BAD_REQUEST);
        }
    }
}
