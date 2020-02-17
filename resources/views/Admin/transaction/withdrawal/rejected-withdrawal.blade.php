@extends('Admin.master',['menu'=>'transactions','submenu'=>'pending_withdrawal'])
@section('title',__('Rejected Withdrawal'))
@section('style')
@endsection
@section('content')
    <div class="user-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-sm-9">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page " >{{__('Rejected withdrawal histories')}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3  text-right acsmrightbtn">
                        <a class="witrowal-btn" href="{{route('adminPendingWithdrawal')}}">{{__('Pending Withdrawal Histories')}}</a>
                    </div>
                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
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
        </div>
    </div>


@endsection
@section('script')
    <script>
        $('#withdrawaltable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            bLengthChange: true,
            responsive: true,
            ajax: '{{route('adminRejectedWithdrawal')}}',
            order:[7,'desc'],
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
                {"data": "transaction_hash"},
                {"data": "created_at"}
            ]
        });
    </script>


@endsection