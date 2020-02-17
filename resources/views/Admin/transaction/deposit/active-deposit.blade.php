@extends('backend.admin-master',['menu'=>'pending_transaction','submenu'=>'deposit'])
@section('title',__('Active Deposit'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('User Details')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <div class="transactions-wrap form-style table-style">
                            <h3 class="user-title">Pending Transactions List</h3>
                            <ul class="transactions-menustyle">
                                <li class="rejected"><a href="{{route('adminRejectedWithdrawal')}}">Rejected Transactions</a></li>
                                <li class="accepted"><a href="{{route('adminActiveWithdrawal')}}">Accepted Transactions</a></li>
                            </ul>
                            <table class="table-responsive" id="deposittable" >
                                <thead>
                                <tr>
                                    <th class="all">{{__('Type')}}</th>
                                    <th>{{__('Sender')}}</th>
                                    <th>{{__('Address / Email')}}</th>
                                    <th>{{__('Receiver')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Fees')}}</th>
                                    <th>{{__('Transaction Id')}}</th>
                                    <th>{{__('Confirmation')}}</th>
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
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('#deposittable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            bLengthChange: false,
            responsive: true,
            ajax: '{{route('adminPendingDeposit')}}',
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