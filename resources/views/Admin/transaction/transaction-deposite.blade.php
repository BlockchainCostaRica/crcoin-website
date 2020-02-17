@extends('Admin.master',['menu'=>'transactions','submenu'=>'all_transaction'])
@section('title',__('All Transaction'))
@section('content')
    <!-- .user-area start -->
    <div class="user-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-sm-10">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page " >{{__('All transactions')}}</li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-area">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="deposittable" width="100%">
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
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('#deposittable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: '{{route('adminDepositHistory')}}',
            order:[7,'desc'],
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
                {"data": "created_at"}
            ]
        });
    </script>
@endsection