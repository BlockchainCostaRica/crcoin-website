@extends('Admin.master',['menu'=>'setting','sub_menu'=>'cms'])
@section('title',__('Landing Page content'))
@section('style')
@endsection
@section('content')
    <div class="coin-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <h3 class="subtitle">{{__('Landing Page content')}}</h3>
                        <ul class="nav user-menu justify-content-start pull-right">
                            <li><a  href="{{route('adminCmsLandingPpageContentAdd')}}"><i class="fa fa-plus"></i> {{__('Add')}}</a></li>
                        </ul>
                        <div class="walletview-wrap form-style table-style">
                            <table class="table-responsive" id="table" width="100%">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Created At')}}</th>
                                    <th>{{__('Actions')}}</th>
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
        $('#table').DataTable({
            processing: true,
            serverSide: true,
//            pageLength: 10,
            responsive: true,
            ajax: '{{route('adminCmsLandingPpageContent')}}',
            order: [2, 'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "image"},
                {"data": "title"},
                {"data": "created_at"},
                {"data": "actions"}
            ]
        });
    </script>
@endsection