<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 8/30/17
 * Time: 5:19 PM
 */

namespace App\Repository;

use App\Model\User\AffiliationCode;
use App\Model\User\AffiliationHistory;
use App\Model\User\ReferralUser;
use App\Model\User\Wallet;
use App\User;
use App\Services\Logger;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AffiliateRepository
{
    // create affiliation code
    public function create($userId)
    {
        $affiliateCodeInput['user_id'] = $userId;
        $affiliateCodeInput['code'] = uniqid($userId);
        $affiliateCodeInput['status'] = 1;

        try {
            $created = AffiliationCode::create($affiliateCodeInput)->id;
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                $this->create($userId);
            }
        }
        return $created;

    }

    // create referral user
    public function createReferralUser($userId, $parentId)
    {
        $created = 0;
        try {
            $data['user_id'] = $userId;
            $data['parent_id'] = $parentId;
            $created = ReferralUser::create($data)->id;
        } catch (\Exception $e) {

        }
        return $created;
    }

    // store data to affiliation history
    public function storeAffiliationHistory($transaction = null)
    {
        if ($transaction != null) {
            $adminSettings = $this->checkAdminSettings();
            $withdrawalUser = $transaction->wallet_id;
            $transactionId = $transaction->transaction_hash;
            $maxReferralLevel = max_level();
            try {
                $userAffiliation = $this->parentReferrals($maxReferralLevel, $withdrawalUser);
                if (!empty($userAffiliation)) {
                    $this->calculateReferralFees($adminSettings, $transactionId, $userAffiliation, $transaction->fees,  $maxReferralLevel);
                }
            } catch (\Exception $e) {
//                dd($e);
            }
//            $logger = app(Logger::class);
            Log::info("DistributeAffiliationBonus[$transactionId]");
            Log::info('distributing affiliation bonus ends');
        }

        return 1;
    }

    // check referral fees setting
    public function checkAdminSettings()
    {
        $adminSettings = allsetting(['fees_level1', 'fees_level2', 'fees_level3', 'fees_level4', 'fees_level5', 'fees_level6', 'fees_level7', 'fees_level8', 'fees_level9', 'fees_level10']);
        if (empty($adminSettings['fees_level1'])) {
            $adminSettings['fees_level1'] = 10;
        }
        if (empty($adminSettings['fees_level2'])) {
            $adminSettings['fees_level2'] = 5;
        }
        if (empty($adminSettings['fees_level3'])) {
            $adminSettings['fees_level3'] = 10;
        }
        if (empty($adminSettings['fees_level4'])) {
            $adminSettings['fees_level4'] = 0;
        }
        if (empty($adminSettings['fees_level5'])) {
            $adminSettings['fees_level5'] = 0;
        }
        if (empty($adminSettings['fees_level6'])) {
            $adminSettings['fees_level6'] = 0;
        }
        if (empty($adminSettings['fees_level7'])) {
            $adminSettings['fees_level7'] = 0;
        }
        if (empty($adminSettings['fees_level8'])) {
            $adminSettings['fees_level8'] = 0;
        }
        if (empty($adminSettings['fees_level9'])) {
            $adminSettings['fees_level9'] = 0;
        }
        if (empty($adminSettings['fees_level10'])) {
            $adminSettings['fees_level10'] = 0;
        }
        return $adminSettings;
    }



    // get parent referral
    public function parentReferrals($maxReferralLevel = 1, $user_id)
    {
        $affiliation = DB::table('referral_users AS ru1')
            ->where('ru1.user_id', $user_id);

        $selectQuery = 'ru1.user_id as user_id, ru1.parent_id as parent_level_user_1';
        for ($i = 1; $i < $maxReferralLevel; $i++) {
            $ru_parent = "ru" . ($i + 1);
            $ru = "ru" . $i;
            $parent_level_user = 'parent_level_user_' . ($i + 1);
            $affiliation = $affiliation->leftJoin("referral_users AS $ru_parent", "$ru.parent_id", '=', "$ru_parent.user_id");
            $selectQuery = $selectQuery . ',' . " $ru_parent.parent_id as $parent_level_user";
        }
        $data = $affiliation->select(DB::raw($selectQuery))->first();

        return $data;
    }

    // calculate referral fees
    protected function calculateReferralFees($adminSettings, $transactionId, $affiliateUsers, $systemFees, $maxReferralLevel = 1)
    {
        try {

        } catch (\Exception $e) {
            return 1;
        }

        $affiliationHistoryData['system_fees'] = $systemFees;
        $affiliationHistoryData['child_id'] = $affiliateUsers->user_id;
        $affiliationHistoryData['status'] = 0;
        $affiliationHistoryData['transaction_id'] = $transactionId;
        $affiliationHistoryData['order_type'] = 1;


        for ($i = 1; $i <= $maxReferralLevel; $i++) {
            $parent_level = 'parent_level_user_' . $i;
            $fees_level = 'fees_level' . $i;
            if ($affiliateUsers->{$parent_level}) {
                $affiliationHistoryData['user_id'] = $affiliateUsers->{$parent_level};
                $fees_percent = isset($adminSettings[$fees_level]) ? $adminSettings[$fees_level] : '0';
                $affiliationHistoryData['amount'] = bcdiv(bcmul($systemFees, $fees_percent), 100);
                $affiliationHistoryData['level'] = $i;
                try {
                    AffiliationHistory::create($affiliationHistoryData);
                } catch (\Exception $e) {

                }
            } else {
                break;
            }
        }
        return 1;
    }


    // deposit the affiliation fees
    public function depositAffiliationFees()
    {
        $firstDay = $start = Carbon::now()->startOfMonth();

        $limit = 100;
        while (true) {
            $affiliationHistory = AffiliationHistory::where('created_at', '<', $firstDay)
                ->where('status', 0)
                ->select('user_id', DB::raw('SUM(amount) AS total'))
                ->groupBy('user_id')
                ->limit($limit)
                ->pluck('total', 'user_id');
            $affiliationHistory = $affiliationHistory->toArray();
            $eligibleUsers = array_keys($affiliationHistory);

            $userWallets = Wallet::whereIn('user_id', $eligibleUsers)
                ->where('is_primary', '1')
                ->get();

            foreach ($userWallets as $userWallet) {
                $userWallet->referral_balance = bcadd($userWallet->referral_balance, $affiliationHistory[$userWallet->user_id]);
                $userWallet->save();
                AffiliationHistory::where('created_at', '<', $firstDay)
                    ->where('status', 0)
                    ->where('user_id', $userWallet->user_id)
                    ->update(['status' => 1]);
            }

            if (count($affiliationHistory) < $limit) {
//
                break;
            }
        }

    }


    // referral children
    public function childrenReferralQuery($maxReferralLevel = 1)
    {

//        $maxReferralLevel = 3;
        $referralAll = DB::table('referral_users AS ru1')->where('ru1.deleted_at', null);
        $selectQuery = 'COUNT(DISTINCT(ru1.user_id)) as level1';
        $allSumQuery = 'COUNT(parent_id) AS referralsLevel0, SUM(level1) as  referralsLevel1';

        for ($i = 1; $i < $maxReferralLevel; $i++) {
            $ru_child = "ru" . ($i + 1);
            $ru = "ru" . $i;
            $level = 'level' . ($i + 1);
            $referralsLevel = 'referralsLevel' . ($i + 1);

            $referralAll->leftJoin("referral_users AS $ru_child", "$ru.user_id", '=', "$ru_child.parent_id");
            $selectQuery = $selectQuery . ', ' . "COUNT(DISTINCT($ru_child.user_id)) as $level";
            $allSumQuery = $allSumQuery . ', ' . "SUM($level) as $referralsLevel";
        }

        $data['referral_all'] = $referralAll;
        $data['select_query'] = $selectQuery;
        $data['all_sum_query'] = $allSumQuery;
        return $data;
    }

}
