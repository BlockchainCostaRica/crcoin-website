@extends('backend.admin-master',['menu'=>'user'])
@section('title',__('User Details'))
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
                        <ul class="nav user-menu justify-content-start">
                            <li><a href="{{route('adminUserDetails',encrypt($user->id))}}?tab=profile">{{__('View Profile')}}</a></li>
                            <li><a href="{{route('adminUserDetails',encrypt($user->id))}}?tab=wallet">{{__('Wallet View')}}</a></li>
                            <li><a href="{{route('adminUserDetails',encrypt($user->id))}}?tab=all_transaction">{{__('All Transaction')}}</a></li>
                            <li><a href="{{route('adminUserDetails',encrypt($user->id))}}?tab=photo_id">{{__('Photo ID')}}</a></li>
                            <li><a class="active" href="{{route('adminUserActivityLog',encrypt($user->id))}}">{{__('Activity Log')}}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active">
                                <div class="walletview-wrap form-style table-style">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table-responsive" id="activity_log" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Action')}}</th>
                                                    <th>{{__('Source')}}</th>
                                                    <th>{{__('Ip Address')}}</th>
                                                    <th>{{__('Location')}}</th>
                                                    <th>{{__('Created At')}}</th>
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
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('#activity_log').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            bLengthChange: false,
            bFilter: false,
            ajax: '{{route('adminUserActivityLog',encrypt($user->id))}}',
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            autoWidth:false,
            order:[4,'desc'],
            columns: [
                {'data': "action"},
                {'data': "source"},
                {'data': "ip_address"},
                {'data': "location"},
                {'data': "created_at"}
            ]
        });
    </script>
@endsection