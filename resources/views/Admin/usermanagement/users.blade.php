@extends('backend.admin-master',['menu'=>'user','submenu'=>'activeuser'])
@section('title',__('Users'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('Users')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <h3 class="subtitle">{{__('User')}}</h3>
                        <ul class="nav user-menu">
                            <li><a @if(isset($tab) && $tab=='userlist') class="active" @endif data-toggle="tab" href="#userlist">{{__('User List')}}</a></li>
                            <li><a @if(isset($tab) && $tab=='useradd') class="active" @endif data-toggle="tab" href="#adduser">{{_('Add User')}}</a></li>
                            <li><a href="{{route('adminDeletedUser')}}">{{__('Deleted User')}}</a></li>
                            <li><a href="{{route('adminSuspendedUsers')}}">{{__('Suspended User')}}</a></li>
                            <li><a href="{{route('adminPendingUsers')}}">{{__('Email Pending')}}</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- user-area start-->
                            <div class="tab-pane fade @if(isset($tab) && $tab=='userlist') show active @endif" id="userlist">
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
                            </div>
                            <!-- user-area end-->
                            <!-- addeduser start -->
                            <div class="tab-pane fade @if(isset($tab) && $tab=='useradd') show active @endif " id="adduser">
                                <div class="userlist-wrap adduser-wrap form-style">
                                    <h4>{{__('User Information')}}</h4>
                                    {{Form::open(['route'=>'adminUserCreate'])}}
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="#">{{__('First Name')}}</label>
                                                <input type="text" name="first_name" placeholder="{{__('First Name')}}" value="{{old('first_name')}}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="#">{{__('Last Name')}}</label>
                                                <input type="text" name="last_name" placeholder="{{__('Last Name')}}" value="{{old('last_name')}}">
                                            </div>
                                            <div class="col-12">
                                                <label for="#">{{__('Email')}} </label>
                                                <input type="text" name="email" placeholder="{{__('email')}}" value="{{old('email')}}">
                                            </div>
                                            <div class="col-12">
                                                <label for="#">{{__('Phone')}} </label>
                                                <input type="text" name="phone" placeholder="{{__('Phone')}}" value="{{old('phone')}}">
                                            </div>
                                            <div class="col-12">
                                                <label for="#">{{__('Role')}} </label>
                                                <select name="role">
                                                    <option value="">{{__('Select')}}</option>
                                                    <option value="2">{{__('User')}}</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="#">{{__('Password')}} </label>
                                                <input type="Password" name="password" placeholder="Password">
                                            </div>
                                            <div class="col-lg-2 col-12">
                                                <button>{{__('save')}}</button>
                                            </div>
                                        </div>
                                    {{Form::close()}}
                                </div>
                            </div>
                            <!-- addeduser end -->
                            <!-- deleteduser-area start-->
                            <div class="tab-pane fade" id="deleteuser">
                                <div class="userlist-wrap deleteduser-wrap table-style form-style">
                                    <h4>{{__('Deleted User Lists')}}</h4>
                                    <table class="table-responsive" id="deleted_user_table" width="100%">
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
                            <!-- deleteduser-area end-->
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
        $('#table').DataTable({
            processing:true,
            serverSide:true,
            pageLength:25,
            bLengthChange:true,
            responsive: true,
            ajax:'{{route('adminUsers')}}',
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
        $('#deleted_user_table').DataTable({
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