@extends('Admin.master',['menu'=>'cp'])
@section('title',__('Custom Page'))
@section('style')
    <link rel="stylesheet" href="{{asset('assets/customPage/jquery-ui.css')}}">
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
                                <li class="page " >{{__('Custom Pages')}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 text-right acsmrightbtn">
                        <a class="add-btn btn-theme" href="{{route('adminCustomPageSettingAdd')}}">{{__('Add custom page')}}</a>
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
                                            <th>{{__('Menu')}}</th>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Created At')}}</th>
                                            <th>{{__('Actions')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody id="sortable"></tbody>
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
            ajax: '{{route('adminCustomPageList')}}',
            order: [2, 'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "key"},
                {"data": "title"},

                {"data": "created_at"},
                {"data": "actions"}
            ]
        });
    </script>
    <script src="{{asset('assets/customPage/jquery-1.12.4.js')}}"></script>
    <script src="{{asset('assets/customPage/jquery-ui.js')}}"></script>
    <script>
        $( function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        } );

        $( "#sortable" ).sortable({

            update: function( ) {
                var l_ar = [];
                $( ".shortable_data" ).each(function( index,data ) {
                    l_ar.push($(this).val());
                });

                $.get( "{{route('customPageOrder')}}?vals="+l_ar, function( data ) {
                    $( ".result" ).html( data );
                    VanillaToasts.create({
                        //  title: 'Message Title',
                        text:data.message,
                        backgroundColor: "linear-gradient(135deg, #73a5ff, #5477f5)",
                        type: 'success',
                        timeout: 3000
                    });
                });
            }
        });

    </script>
@endsection