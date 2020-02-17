@extends('backend.admin-master',['menu'=>'user','submenu'=>'activeuser'])
@section('title',__('Deleted User'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('Deleted User')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <h3 class="subtitle">{{__('Deleted User')}}</h3>
                        <ul class="nav user-menu">
                            <li><a href="{{route('adminUsers')}}?tab=userlist">{{__('User List')}}</a></li>
                            <li><a href="{{route('adminUsers')}}?tab=useradd">{{_('Add User')}}</a></li>
                            <li><a class="active" href="{{route('adminDeletedUser')}}">{{__('Deleted User')}}</a></li>
                            <li><a href="{{route('adminSuspendedUsers')}}">{{__('Suspended User')}}</a></li>
                            <li><a href="{{route('adminPendingUsers')}}">{{__('Email Pending')}}</a></li>
                        </ul>
                        <!-- user-area start-->
                            <div class="userlist-wrap form-style table-style">
                                <table class="table-responsive table" id="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th>{{__('First Name')}}</th>
                                        <th>{{__('Last Name')}}</th>
                                        <th>{{__('Email ID')}}</th>
                                        <th>{{__('Created At')}}</th>
                                        <th>{{__('Activity')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        <!-- user-area end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('#table').DataTable({
            processing:true,
            serverSide:true,
            pageLength:25,
            bLengthChange:true,
            responsive: true,
            ajax:'{{route('adminDeletedUser')}}',
            order:[3,'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns:[
                {"data":"first_name"},
                {"data":"last_name"},
                {"data":"email"},
                {"data":"created_at"},
                {"data":"actions",orderable:false,searchable:false},
            ]
        });
    </script>
@endsection