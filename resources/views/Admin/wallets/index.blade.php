@extends('Admin.master',['menu'=>'crypto_wallet','submenu'=>'wallet'])
@section('title', 'User Wallets')
@section('style')
@endsection
@section('content')
    <div class="user-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page " >{{__('All wallets')}}</li>
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
                                    <table class="table" id="wallettable">
                                        <thead>
                                        <tr>
                                            <th>{{__('Wallet Name')}}</th>
                                            <th>{{__('User Name')}}</th>
                                            <th>{{__('User Email')}}</th>
                                            <th>{{__('Balance')}}</th>
                                            <th>{{__('Update Date')}}</th>
                                        </tr>
                                        </thead>
                                    </table>
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
        $('#wallettable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            bLengthChange: true,
            responsive:true,
            ajax: '{{route('adminWallets')}}',
            order:[4,'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "name"},
                {"data": "user_name"},
                {"data": "email",name: 'users.email'},
                {"data": "balance"},
                {"data": "created_at"}
            ]
        });
    </script>

@endsection