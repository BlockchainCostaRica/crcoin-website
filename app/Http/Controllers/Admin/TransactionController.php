<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\TransactionService;
use App\Jobs\Withdrawal;
use App\Model\User\BuyCoinHistories;
use App\Model\User\DepositeTransaction;
use App\Model\User\Wallet;
use App\Model\User\WithdrawHistory;
use App\Services\BitCoinApiService;
use App\Services\CommonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    // transaction view page
    public function adminTransaction()
    {
        return view('backend.admin.transaction.transaction');
    }

    // deposit history
    public function adminDepositHistory(Request $request)
    {
        if ($request->ajax()) {
            $deposit = DepositeTransaction::select('deposite_transactions.address'
                , 'deposite_transactions.amount'
                , 'deposite_transactions.fees'
                , 'deposite_transactions.transaction_id'
                , 'deposite_transactions.confirmations'
                , 'deposite_transactions.address_type as addr_type'
                , 'deposite_transactions.created_at'
                , 'deposite_transactions.sender_wallet_id'
                , 'deposite_transactions.receiver_wallet_id'
                , 'deposite_transactions.status'
            );

            return datatables()->of($deposit)
                ->addColumn('address_type', function ($dpst) {
                    if ($dpst->addr_type == 'internal_address') {
                        return 'External';
                    } else {
                        return addressType($dpst->addr_type);
                    }

                })
                ->addColumn('sender', function ($dpst) {
                    return isset($dpst->senderWallet->user) ? $dpst->senderWallet->user->first_name . ' ' . $dpst->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($dpst) {
                    return isset($dpst->receiverWallet->user) ? $dpst->receiverWallet->user->first_name . ' ' . $dpst->receiverWallet->user->last_name : '';
                })
                ->make(true);
        }

        return view('Admin.transaction.transaction-deposite');
    }

    // withdrawal history
    public function adminWithdrawalHistory(Request $request)
    {
        if ($request->ajax()) {
            $withdrawal = Withdrawal::join('wallets', 'wallets.id', '=', 'withdrawals.receiver_wallet_id')
                ->select('withdrawals.address'
                    , 'withdrawals.amount'
                    , 'withdrawals.fees'
                    , 'withdrawals.transaction_id'
                    , 'withdrawals.confirmations'
                    , 'withdrawals.address_type as addr_type'
                    , 'withdrawals.created_at'
                    , 'withdrawals.sender_wallet_id'
                    , 'withdrawals.receiver_wallet_id'
                );
            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($dpst) {
                    return addressType($dpst->addr_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    return isset($wdrl->senderWallet->user) ? $wdrl->senderWallet->user->first_name . ' ' . $wdrl->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($wdrl) {
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : '';
                })
                ->make(true);
        }

        return view('Admin.transaction.transaction-withdrawal');
    }

    public function adminPendingDeposit(Request $request)
    {
        if ($request->ajax()) {
            $deposit = BuyCoinHistories::select('*')->where(['status' => STATUS_PENDING]);

            return datatables()->of($deposit)
                ->addColumn('payment_type', function ($dpst) {
                    $html  = '';
                    if ($dpst->type == BANK_DEPOSIT) {
                      $html .= receipt_view_html(imageSrc($dpst->bank_sleep,IMG_SLEEP_VIEW_PATH));
                    } else {
                        $html .= byCoinType($dpst->type);
                    }

                    return $html;
                })
                ->addColumn('email', function ($dpst) {
                    return $dpst->user()->first()->email;
                })
                ->addColumn('date', function ($dpst) {
                    return $dpst->created_at;
                })
                ->addColumn('action', function ($wdrl) {
                    $action = '<ul>';
                    $action .= accept_html('adminAcceptPendingBuyCoin',encrypt($wdrl->id));
                    $action .= reject_html('adminRejectPendingBuyCoin',encrypt($wdrl->id));
                    $action .= '<ul>';
                    return $action;
                })
                ->rawColumns(['payment_type','action'])
                ->make(true);
        }

        return view('Admin.transaction.deposit.pending-deposit');
    }

    // rejected deposit list
    public function adminRejectedDeposit(Request $request)
    {
        if ($request->ajax()) {
            $deposit = Deposit::select(
                'deposits.address'
                , 'deposits.amount'
                , 'deposits.fees'
                , 'deposits.transaction_id'
                , 'deposits.confirmations'
                , 'deposits.address_type as addr_type'
                , 'deposits.created_at'
                , 'deposits.sender_wallet_id'
                , 'deposits.receiver_wallet_id'
            )->where(['deposits.status' => STATUS_REJECT]);

            return datatables()->of($deposit)
                ->addColumn('address_type', function ($dpst) {
                    return addressType($dpst->addr_type);
                })
                ->addColumn('sender', function ($dpst) {
                    return isset($dpst->senderWallet->user) ? $dpst->senderWallet->user->first_name . ' ' . $dpst->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($dpst) {
                    return isset($dpst->receiverWallet->user) ? $dpst->receiverWallet->user->first_name . ' ' . $dpst->receiverWallet->user->last_name : '';
                })
                ->make(true);
        }
        return view('backend.admin.transaction.deposit.pending-deposit');

    }

    public function adminActiveDeposit(Request $request)
    {
        if ($request->ajax()) {
            $deposit = Deposit::select(
                'deposits.address'
                , 'deposits.amount'
                , 'deposits.fees'
                , 'deposits.transaction_id'
                , 'deposits.confirmations'
                , 'deposits.address_type as addr_type'
                , 'deposits.created_at'
                , 'deposits.sender_wallet_id'
                , 'deposits.receiver_wallet_id'
            )->where(['deposits.status' => STATUS_SUCCESS]);

            return datatables()->of($deposit)
                ->addColumn('address_type', function ($dpst) {
                    return addressType($dpst->addr_type);
                })
                ->addColumn('sender', function ($dpst) {
                    return isset($dpst->senderWallet->user) ? $dpst->senderWallet->user->first_name . ' ' . $dpst->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($dpst) {
                    return isset($dpst->receiverWallet->user) ? $dpst->receiverWallet->user->first_name . ' ' . $dpst->receiverWallet->user->last_name : '';
                })
                ->make(true);
        }
        return view('backend.admin.transaction.deposit.pending-deposit');

    }

    // pending withdrawal list
    public function adminPendingWithdrawal(Request $request)
    {
        if ($request->ajax()) {
            $withdrawal = WithdrawHistory::select(
                'withdraw_histories.id',
                'withdraw_histories.address'
                , 'withdraw_histories.amount'
                , 'withdraw_histories.fees'
                , 'withdraw_histories.transaction_hash'
                , 'withdraw_histories.confirmations'
                , 'withdraw_histories.address_type as addr_type'
                , 'withdraw_histories.created_at'
                , 'withdraw_histories.wallet_id as sender_wallet_id'
                , 'withdraw_histories.receiver_wallet_id'
            )->where(['withdraw_histories.status' => STATUS_PENDING]);

            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    return isset($wdrl->senderWallet->user) ? $wdrl->senderWallet->user->first_name . ' ' . $wdrl->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($wdrl) {
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : '';
                })
                ->addColumn('actions', function ($wdrl) {
                    $action = '<ul>';
                    $action .= accept_html('adminAcceptPendingWithdrawal',encrypt($wdrl->id));
                    $action .= reject_html('adminRejectPendingWithdrawal',encrypt($wdrl->id));
                    $action .= '<ul>';

                    return $action;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('Admin.transaction.withdrawal.pending-withdrawal');
    }

    // rejected withdrawal list
    public function adminRejectedWithdrawal(Request $request)
    {
        if ($request->ajax()) {
            $withdrawal = WithdrawHistory::select(
                'withdraw_histories.address'
                , 'withdraw_histories.amount'
                , 'withdraw_histories.fees'
                , 'withdraw_histories.transaction_hash'
                , 'withdraw_histories.confirmations'
                , 'withdraw_histories.address_type as addr_type'
                , 'withdraw_histories.created_at'
                , 'withdraw_histories.wallet_id as sender_wallet_id'
                , 'withdraw_histories.receiver_wallet_id'
            )->where(['withdraw_histories.status' => STATUS_REJECTED]);

            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    return isset($wdrl->senderWallet->user) ? $wdrl->senderWallet->user->first_name . ' ' . $wdrl->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($wdrl) {
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : '';
                })
                ->make(true);
        }

        return view('Admin.transaction.withdrawal.rejected-withdrawal');
    }

    // active withdrawal list
    public function adminActiveWithdrawal(Request $request)
    {
        if ($request->ajax()) {
            $withdrawal = Withdrawal::select(
                'withdrawals.address'
                , 'withdrawals.amount'
                , 'withdrawals.fees'
                , 'withdrawals.transaction_id'
                , 'withdrawals.confirmations'
                , 'withdrawals.address_type as addr_type'
                , 'withdrawals.created_at'
                , 'withdrawals.sender_wallet_id'
                , 'withdrawals.receiver_wallet_id'
            )->where(['withdrawals.status' => STATUS_SUCCESS]);

            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    return isset($wdrl->senderWallet->user) ? $wdrl->senderWallet->user->first_name . ' ' . $wdrl->senderWallet->user->last_name : '';
                })
                ->addColumn('receiver', function ($wdrl) {
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : '';
                })
                ->make(true);
        }

        return view('backend.admin.transaction.withdrawal.active-withdrawal');
    }

    // accept process of pending withdrawal
    public function adminAcceptPendingWithdrawal($id)
    {
        if (isset($id)) {
            try {
                $wdrl_id = decrypt($id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            $transaction = WithdrawHistory::with('wallet')->with('users')->where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();

            if (!empty($transaction)) {
                if ($transaction->address_type == ADDRESS_TYPE_INTERNAL) {
//                    $transactionArray = [
//                        'address' => $transaction->address,
//                        'address_type' => $transaction->address_type,
//                        'amount' => $transaction->amount,
//                        'fees' => 0,
//                        'transaction_id' => $transaction->transaction_hash,
//                        'confirmations' => 0,
//                        'status' => STATUS_SUCCESS,
//                        'sender_wallet_id' => $transaction->wallet_id,
//                        'receiver_wallet_id' => $transaction->receiver_wallet_id
//                    ];

                    $deposit = DepositeTransaction::where(['transaction_id' =>$transaction->transaction_hash, 'address' => $transaction->address])->update(['status' => STATUS_SUCCESS]);

                    Wallet::where(['id' => $transaction->receiver_wallet_id])->increment('balance', $transaction->amount);
                    $transaction->status = STATUS_SUCCESS;
                    $transaction->save();

                    return redirect()->back()->with('success', 'Pending withdrawal accepted Successfully.');

                } elseif ($transaction->address_type == ADDRESS_TYPE_EXTERNAL) {
                    $btc_service = new TransactionService();

                    $response = $btc_service->external_transfer($transaction->address, $transaction->amount, Auth::user()->id, True, $transaction->wallet->user->id);

                    if ($response['status'] === false) {
                        return redirect()->back()->with('dismiss', 'Something went wrong! Please try again!');
                    }
                    $transaction->transaction_hash = $response['transaction_id'];
                    $transaction->status = STATUS_SUCCESS;
                    $transaction->update();
                    $commonservice = new \App\Http\Services\CommonService();
//                    $commonservice  = $commonservice->affiliationBonus($transaction);

                    return redirect()->back()->with('success', 'Pending withdrawal accepted Successfully.');
                }
            }

            return redirect()->back()->with('dismiss', 'Something went wrong! Please try again!');
        }
    }

    // pending withdrawal reject process
    public function adminRejectPendingWithdrawal($id)
    {
        if (isset($id)) {
            try {
                $wdrl_id = decrypt($id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            $transaction = WithdrawHistory::where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();

            if (!empty($transaction)) {
                if ($transaction->address_type == ADDRESS_TYPE_INTERNAL) {

                    Wallet::where(['id' => $transaction->wallet_id])->increment('balance', $transaction->amount);
                    $transaction->status = STATUS_REJECTED;
                    $transaction->update();

                    $deposit = DepositeTransaction::where(['transaction_id' =>$transaction->transaction_hash, 'address' => $transaction->address])->update(['status' => STATUS_REJECTED]);

                    return redirect()->back()->with('success', 'Pending withdrawal rejected Successfully.');
                } elseif ($transaction->address_type == ADDRESS_TYPE_EXTERNAL) {
//                    $tr = new TransactionService();

//                    $response = $tr->external_transfer($transaction->address, $transaction->amount, Auth::user()->id, True, $transaction->wallet->user->id);
//                   dd($response);
//                    if ($response['status'] == true) {
                    $transaction->status = STATUS_REJECTED;

                    $transaction->update();

                    return redirect()->back()->with('success', 'Pending Withdrawal rejected Successfully.');
                }
            }

            return redirect()->back()->with('dismiss', 'Something went wrong! Please try again!');
        }
    }


    // pending coin accept process
    public function adminAcceptPendingBuyCoin($id)
    {
        if (isset($id)) {
            try {
                $wdrl_id = decrypt($id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            $transaction = BuyCoinHistories::where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();

            $primary = Wallet::where('user_id',$transaction->user_id)->where('is_primary',1)->first();
            $primary->increment('balance', $transaction->coin);
            $transaction->status = STATUS_SUCCESS;
            $transaction->save();

            return redirect()->back()->with('success', 'Request accepted successfully');
        }
    }

    // pending coin reject process
    public function adminRejectPendingBuyCoin($id)
    {
        if (isset($id)) {
            try {
                $wdrl_id = decrypt($id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            $transaction = BuyCoinHistories::where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();
            $transaction->status = STATUS_REJECTED;
            $transaction->update();

            return redirect()->back()->with('success', 'Request cancelled successfully');
        }
    }

    // transaction hash details
    public function adminTransactionHashDetails(Request $request)
    {
        if ($request->method() == "POST") {
            $validator = Validator::make($request->all(), [
                'hash' => 'required'
            ]);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $response = [
                    'success' => false,
                    'errors' => $errors
                ];
                return redirect()->back()->withInput()->with('errors', $validator->errors());
            }

            $tcnCredentials = allsetting();
            $api = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));

            $transaction = $api->getTranscation($request->hash);
            if ($transaction) {
                $response = $transaction;
            } else {
                $response = ('Not a valid transaction');
            }

            return view('Admin.transaction.transaction-hash-details', ['response' => $response]);
        }
        return view('Admin.transaction.transaction-hash-details');
    }
}
