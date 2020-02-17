@extends('Admin.master',['menu'=>'users','submenu'=>'users'])
@section('title', 'Users')
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
                                <li class="page " >{{__('User management')}}</li>
                                <li class="page active">{{__('User')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- tabs style start here -->
                            <div class="single-tab">
                                <div class="section-body ">
                                    <div class="nav nav-pills" id="tab" role="tablist" >
                                        <a class="nav-link active" data-id="active_users" id="home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="home" aria-selected="true"> <span class="flaticon-user one"></span> {{__('User list')}}</a>
                                        <a class="nav-link add_user" data-id="profile_tab" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> <span class="flaticon-user-3 two"></span> {{__('Add user')}}</a>
                                        <a class="nav-link" data-id="suspend_user" id="messages-tab" data-toggle="pill" href="#home" role="tab" aria-controls="messages" aria-selected="false"> <span class="flaticon-delete three"></span> {{__('Suspended user')}}</a>
                                        <a class="nav-link" data-id="deleted_user" id="settings-tab" data-toggle="pill" href="#home" role="tab" aria-controls="settings" aria-selected="false"> <span class="flaticon-delete-user four"></span> {{__('Deleted user')}}</a>
                                        <a class="nav-link" data-id="email_pending" id="res-tab" data-toggle="pill" href="#home" role="tab" aria-controls="settings" aria-selected="false"> <span class="flaticon-email five"></span>{{__(' Email pending')}}</a>
                                    </div>
                                    <div class="tab-content" id="tabContent">
                                        <div class="tab-pane fade show active"  id="home" role="tabpanel" aria-labelledby="home-tab">

                                            <div class="table-area">
                                                <div class="table-responsive">
                                                    <table class="table" id="users_table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">{{__('User Name')}}</th>
                                                            <th scope="col">{{__('Email ID')}}</th>
                                                            <th scope="col">{{__('Role')}}</th>
                                                            <th scope="col">{{__('Status')}}</th>
                                                            <th scope="col">{{__('Created At')}}</th>
                                                            <th scope="col">{{__('Activity')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- table-area start here -->
                                        </div>
                                        <div class="tab-pane fade add_user" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <!-- page-title area start here -->
                                            <div class="page-title">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="page-title-inner">
                                                            <div class="title">
                                                                <h3>{{__('Add User')}}</h3>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- page-title area end here -->
                                            <!-- from area start here -->
                                            <div class="form-area plr-65">
                                                <form action="{{route('admin.UserAddEdit')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="firstname">{{__('First Name')}}</label>
                                                                <input type="text" name="first_name" class="form-control" id="firstname" value="{{old('first_name')}}"  placeholder="First Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lastname">{{__('Last Name')}}</label>
                                                                <input name="last_name" type="text" class="form-control" id="lastname" value="{{old('last_name')}}"  placeholder="Last Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="email">{{__('Email')}}</label>
                                                                <input type="email" name="email" class="form-control" id="email" value="{{old('email')}}" placeholder="Email address">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lastname">{{__('Phone Number')}}</label>
                                                                <input type="text" class="form-control" id="phone" name="phone" value="{{old('phone')}}"  placeholder="phone">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{__('Role')}}</label>
                                                                <select name="type" class="wide">
                                                                    <option value="{{USER_ROLE_ADMIN}}">{{__('Admin')}}</option>
                                                                    <option data-display="User" value="{{USER_ROLE_USER}}">{{__('User')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button class="button-primary ">{{__('Save')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- from area start here -->
                                        </div>
                                        <div class="tab-pane fade"  id="messages" role="tabpanel" aria-labelledby="messages-tab">
                                            <!-- page-title area start here -->
                                            <div class="page-title">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="page-title-inner">
                                                            <div class="title">
                                                                <h3>Suspended User</h3>
                                                            </div>
                                                            <div class="search-right">
                                                                <form action="#">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="searchpage" placeholder="Search">
                                                                        <button><span class="flaticon-search"></span></button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- page-title area end here -->
                                            <!-- table-area start here -->
                                            <div class="table-area">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">First Name</th>
                                                            <th scope="col">Last Name</th>
                                                            <th scope="col">Email ID</th>
                                                            <th scope="col">Created At</th>
                                                            <th scope="col">Activity</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- table-area start here -->
                                        </div>
                                        <div class="tab-pane fade"  id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                            <!-- page-title area start here -->
                                            <div class="page-title">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="page-title-inner">
                                                            <div class="title">
                                                                <h3>Deleted user</h3>
                                                            </div>
                                                            <div class="search-right">
                                                                <form action="#">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="searchpage" placeholder="Search">
                                                                        <button><span class="flaticon-search"></span></button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- page-title area end here -->
                                            <!-- table-area start here -->
                                            <div class="table-area">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">First Name</th>
                                                            <th scope="col">Last Name</th>
                                                            <th scope="col">Email ID</th>
                                                            <th scope="col">Created At</th>
                                                            <th scope="col">Activity</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- table-area start here -->
                                        </div>
                                        <div class="tab-pane fade"  id="res" role="tabpanel" aria-labelledby="res-tab">
                                            <!-- page-title area start here -->
                                            <div class="page-title">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="page-title-inner">
                                                            <div class="title">
                                                                <h3>Email pendings</h3>
                                                            </div>
                                                            <div class="search-right">
                                                                <form action="#">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="searchpage" placeholder="Search">
                                                                        <button><span class="flaticon-search"></span></button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- page-title area end here -->
                                            <!-- table-area start here -->
                                            <div class="table-area">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">First Names</th>
                                                            <th scope="col">Last Name</th>
                                                            <th scope="col">Email ID</th>
                                                            <th scope="col">Created At</th>
                                                            <th scope="col">Activity</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- table-area start here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tabs style start here -->
                        </div>
                    </div>

                </div>
                <!-- pagination-area start here -->

            </div>
        </div>
    </div>

@endsection

@section('script')
    @if(isset($errors->all()[0]))
        <script>

            $('.tab-pane').removeClass('active show');
            $('.nav-link').removeClass('active show');
            $('.add_user').addClass('active show');
            $('#profile-tab').addClass('active show');

        </script>
    @endif
    <script>
       function getTable(type) {

           var table =  $('#users_table').DataTable({
               processing: true,
               serverSide: true,
               pageLength: 10,
               retrieve: true,
               bLengthChange: true,
               responsive: true,
               ajax: '{{route('admin.users')}}?type='+type,
               order: [3, 'desc'],
               autoWidth: false,
               language: {
                        paginate: {
                            next: 'Next &#8250;',
                            previous: '&#8249; Previous'
                        }
                    },
               columns: [
                   {"data": "first_name","orderable": false},
                   {"data": "email","orderable": false},
                   {"data": "type","orderable": false},
                   {"data": "status","orderable": false},
                   {"data": "created_at","orderable": false},
                   {"data": "activity","orderable": false}
               ],
           });

       }
       $(document.body).on('click','.nav-link',function () {
         var id = $(this).data('id');
           if (id != 'undefined'){
                $('#users_table').DataTable().destroy();
                getTable(id)
            }

       });
       getTable('active_users');
    </script>

@endsection