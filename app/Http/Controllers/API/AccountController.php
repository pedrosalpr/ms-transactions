<?php

namespace App\Http\Controllers\API;

use App\Entities\Users\User;
use App\Exceptions\Gateways\ClientApiException;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountBalance;
use App\Services\Users\UserAccount;
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
            $user = $this->getUserAccountEntity($id);
            $balance = (new AccountBalance($user))->balance();
            return response()->json(['value' => $balance], Response::HTTP_OK);
        } catch (ClientApiException $ex) {
            return response()->json($ex->report(), Response::HTTP_NOT_FOUND);
        }
    }

    private function getUserAccountEntity(int $id): User
    {
        $userAccount = resolve(UserAccount::class, [UserClientApi::class]);
        return $userAccount->getEntity($id);
    }
}
