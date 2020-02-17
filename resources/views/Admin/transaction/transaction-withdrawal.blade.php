@extends('Admin.master',['menu'=>'crypto_wallet','submenu'=>'all_transaction'])
@section('title',__('All Transaction'))
@section('content')
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <div class="transactions-wrap form-style table-style">
                            <h3 class="subtitle">{{__('Withdrawal')}}</h3>
                            <ul class="nav transactions-menu">
                                <li><a class="active" href="{{route('adminWithdrawalHistory')}}"> {{__('WITHDRAWAL')}}</a></li>
                                <li><a  href="{{route('adminDepositHistory')}}">{{__('DEPOSIT')}}</a></li>
                            </ul>
                            <div class="tab-content">
                                <table class="table-responsive table" id="withdrawaltable" width="100%">
                                    <thead>
                                    <tr>
                                        <th class="mobile-l">{{__('Type')}}</th>
                                        <th class="mobile-l">{{__('Sender')}}</th>
                                        <th class="all">{{__('Address')}}</th>
                                        <th class="mobile-l">{{__('Receiver')}}</th>
                                        <th class="all">{{__('Amount')}}</th>
                                        <th class="mobile-l">{{__('Fees')}}</th>
                                        <th class="all">{{__('Transaction Id')}}</th>
                                        <th class="mobile-l">{{__('Confirmation')}}</th>
                                        <th class="all">{{_('Update Date')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('#withdrawaltable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            bLengthChange: true,
            responsive: true,
            ajax: '{{route('adminWithdrawalHistory')}}',
            order:[8,'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "address_type"},
                {"data": "sender"},
                {"data": "address"},
                {"data": "receiver"},
                {"data": "amount"},
                {"data": "fees"},
                {"data": "transaction_id"},
                {"data": "confirmations"},
                {"data": "created_at"}
            ]
        });
    </script>


@endsection