@extends('Admin.master',['menu'=>'setting','sub_menu'=>'faq'])
@section('title',__('FAQS'))
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
                                <li class="page " >{{__('FAQS')}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <a class="add-btn" href="{{route('adminFaqAdd')}}">{{__('Add Faq page')}}</a>
                    </div>
                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-area">
                                <div class="table-responsive">
                                    <table class="table" id="table">
                                        <thead>
                                        <tr>
                                            <th>{{__('Question')}}</th>
                                            <th>{{__('Priority')}}</th>
                                            <th>{{__('Created At')}}</th>
                                            <th>{{__('Actions')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
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
        $('#table').DataTable({
            processing: true,
            serverSide: true,
//            pageLength: 10,
            responsive: true,
            ajax: '{{route('adminFaqList')}}',
            order: [2, 'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "question"},
                {"data": "priority"},
                {"data": "created_at"},
                {"data": "actions"}
            ]
        });
    </script>
@endsection