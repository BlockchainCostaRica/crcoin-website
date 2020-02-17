@extends('backend.admin-master',['menu'=>'pending_transaction','submenu'=>'withdrawal'])
@section('title',__('Active Withdrawal'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('Active Withdrawals')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-inner">
                        <!-- breadcrumb-area start here -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-area">
                                    <div class="table-responsive">
                                        <table class="table" id="withdrawaltable">
                                            <thead>
                                            <tr>
                                                <th class="mobile-l">{{__('Type')}}</th>
                                                <th class="mobile-l">{{__('Sender')}}</th>
                                                <th class="all">{{__('Address')}}</th>
                                                <th class="mobile-l">{{__('Receiver')}}</th>
                                                <th class="all">{{__('Amount')}}</th>
                                                <th class="mobile-l">{{__('Fees')}}</th>
                                                <th class="all">{{__('Transaction Id')}}</th>
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
            bLengthChange: false,
            responsive: true,
            ajax: '{{route('adminRejectedWithdrawal')}}',
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
                {"data": "created_at"}
            ]
        });
    </script>

@endsection