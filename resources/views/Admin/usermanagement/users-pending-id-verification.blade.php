@extends('Admin.master',['menu'=>'users','submenu'=>'pendingid'])
@section('title',__('Pending Id Verified User'))
@section('content')
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="breadcrumb-area">
                        <ul class="mt-3">
                            <li class="page " >{{__('User management')}}</li>
                            <li class="page active">{{__('Pending id verification')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-area">
                        <div class="table-responsive">
                            <h3 class="subtitle">{{__('Users')}}</h3>
                            <div class="userlist-wrap  deleteduser-wrap table-style form-style">
                                <table class="table" id="pending_verify" width="100%">
                                    <thead>
                                    <tr>
                                        <th>{{__('First Name')}}</th>
                                        <th>{{__('Last Name')}}</th>
                                        <th class="all">{{__('Email ID')}}</th>
                                        <th class="all">{{__('Created At')}}</th>
                                        <th class="all">{{__('Actions')}}</th>
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
        $('#pending_verify').DataTable({
            processing:true,
            serverSide:true,
            pageLength:10,
            bLengthChange:true,
            responsive: true,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            ajax:'{{route('adminUserIdVerificationPending')}}',
            order:[3,'desc'],
            autoWidth:false,
            columns:[
                {"data":"first_name",searchable:false},
                {"data":"last_name",searchable:false},
                {"data":"email",searchable:false},
                {"data":"updated_at",searchable:false},
                {"data":"actions",orderable:false,searchable:false},
            ]
        });

    </script>
@endsection