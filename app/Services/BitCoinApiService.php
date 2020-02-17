<?php


namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BitCoinApiService
{
    protected $api;

    public function __construct($user, $pass, $host, $port)
    {
        $this->api = new BaseBitCoin($user, $pass, $host, $port);
    }

    public function verifyAddress($address)
    {
        //return true;
        $response = $this->api->validateaddress($address);

        return $response['isvalid'];
    }

    public function sendToAddress($address, $amount, $givenAuthId, $adminId = null)
    {


        $fromQueue = 'From Auth';
        $userId = $givenAuthId;

        if (empty($adminId)) {
            $fromQueue = 'From Queue';
        }

        if (empty($givenAuthId)) {
            die;
        }

        $byAdmin = '';


        if (!empty($adminId)) {
            $byAdmin = ' Approved By Admin. Admin UserID: ' . $adminId;
        }
        $comment = "$fromQueue , User ID: " . $userId . $byAdmin;

//        return $response = $this->api->sendtoaddress($address, custom_number_format($amount), $comment);
        return $response = $this->api->sendtoaddress($address, custom_number_format($amount), $comment);
    }

    public function sendFrom($toAddress, $amount, $fromAccount = '')
    {
        return $response = $this->api->sendfrom($fromAccount, $toAddress, customNumberFormat($amount));
    }

    public function getNewAddress($account = '')
    {
        $response = $this->api->getnewaddress($account);

        return $response ? $response : false;
    }

    public function getAllAccounts()
    {
        $response = $this->api->listaccounts();

        return $response ? $response : false;
    }

    public function getReceivedByAddress($address, $minConfirm = 6)
    {
        $response = $this->api->getreceivedbyaddress($address, $minConfirm);

        return $response ? $response : false;
    }

    public function listReceivedByAddress($minConfirm = 1)
    {
        $response = $this->api->listreceivedbyaddress($minConfirm);

        return $response ? $response : false;
    }

    public function walletPassPhrase($passPhrase, $timeOut)
    {
        $response = $this->api->walletpassphrase($passPhrase,$timeOut);

        return $response ? $response : false;
    }

    public function walletPassPhraseChange($oldPassPhrase, $newPassPhrase)
    {
        $response = $this->api->walletpassphrase($oldPassPhrase,$newPassPhrase);

        return $response ? $response : false;
    }

    public function getTranscation($transcationId)
    {
        $response = $this->api->gettransaction($transcationId);

        return $response;
    }

    public function getBalance($account = '')
    {
        $response = $this->api->getbalance($account);

        return $response;
    }
    public function getLastTransactions($last = 10)
    {
        return $this->api->listtransactions('',$last);
    }
}