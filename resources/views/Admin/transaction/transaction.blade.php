@extends('backend.admin-master',['menu'=>'transaction'])
@section('title',__('All Transaction'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('All Transaction')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <div class="transactions-wrap form-style table-style">
                            <h3 class="subtitle">{{__('Transactions')}}</h3>
                            <ul class="nav transactions-menu">
                                <li><a class="active" data-toggle="tab" href="#sent"> {{__('SENT')}}</a></li>
                                <li><a data-toggle="tab" href="#received">{{__('Received')}}</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- sent-area start-->
                                <div class="tab-pane fade show active" id="sent">
                                    <table class="table-responsive table" id="deposittable" width="100%">
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
                                <!-- Recived-area start-->
                                <div class="tab-pane fade" id="received">

                                    <table class="table-responsive table" id="withdrawaltable" width="100%">
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
            ajax: '{{route('adminDepositHistory')}}',
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
    <script>
        $('#withdrawaltable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            bLengthChange: false,
            responsive: true,
            ajax: '{{route('adminWithdrawalHistory')}}',
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