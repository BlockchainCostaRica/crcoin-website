<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\BuyCoinRequest;
use App\Repository\CoinRepository;
use App\Repository\WalletRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CoinController extends Controller
{
    // buy coin process
    public function buyCoinProcess(BuyCoinRequest $request)
    {
        $data = ['success'=> false, 'message' => __('Invalid request')];
        $coinRepo = app(CoinRepository::class);

        $response = $coinRepo->buyCoin($request);
        if ($response) {
            $data = $response;
        }
        $data['available_balance'] = app(WalletRepository::class)->availableBalance(Auth::user()->id);
        return $data;
    }
}
