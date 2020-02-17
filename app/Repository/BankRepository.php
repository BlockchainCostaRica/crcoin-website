<?php
namespace App\Repository;
use App\Model\Admin\Bank;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BankRepository
{
// bank  save process
    public function bankSaveProcess($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $data = [
                'account_holder_name' => $request->account_holder_name,
                'account_holder_address' => $request->account_holder_address,
                'bank_name' => $request->bank_name,
                'bank_address' => $request->bank_address,
                'iban' => $request->iban,
                'swift_code' => $request->swift_code,
                'country' => $request->country,
                'note' => $request->note,
                'status' => $request->status,
            ];
            if (isset($request->edit_id)) {
                $item = Bank::where('id', $request->edit_id)->first();
            }

            if(!empty($request->edit_id)) {
                $update = Bank::where(['id' => $request->edit_id])->update($data);
                if ($update) {
                    $response = [
                        'success' => true,
                        'message' => __('Bank updated successfully')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to update')
                    ];
                }
            } else {
                $saveData= Bank::create($data);
                if ($saveData) {
                    $response = [
                        'success' => true,
                        'message' => __('New bank created successfully.')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to create')
                    ];
                }
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
//                'message' => __('Something Went wrong !')
            ];
            return $response;
        }

        return $response;
    }

// delete bank
    public function deleteBank($id)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        DB::beginTransaction();
        try {
            $item = Bank::where('id',$id)->first();
            if (isset($item)) {
                $delete = $item->delete();
                if ($delete) {
                    $response = [
                        'success' => true,
                        'message' => __('Bank deleted successfully.')
                    ];
                } else {
                    DB::rollBack();
                    $response = [
                        'success' => false,
                        'message' => __('Operation failed.')
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => __('Data not found.')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
        DB::commit();
        return $response;
    }

}
