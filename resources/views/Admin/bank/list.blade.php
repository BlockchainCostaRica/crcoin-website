@extends('Admin.master',['menu'=>'bank','submenu'=>'bank'])
@section('title','Bank List')
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
                                <li class="page " >{{__('Bank management')}}</li>
                                <li class="page active">{{__('Bank List')}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 text-right">
                        <a class="add-btn btn-theme" href="{{route('bankAdd')}}">{{__('Add New Bank')}}</a>
                    </div>
                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- tabs style start here -->
                            <div class="table-area">
                                <div class="table-responsive">
                                    <table class="table" id="users_table">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Bank Name')}}</th>
                                            <th scope="col">{{__('Holder Name')}}</th>
                                            <th scope="col">{{__('IBAN')}}</th>
                                            <th scope="col">{{__('Country')}}</th>
                                            <th scope="col">{{__('Created At')}}</th>
                                            <th scope="col">{{__('Status')}}</th>
                                            <th scope="col">{{__('Activity')}}</th>
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

@endsection

@section('script')
    <script>
        $('#users_table').DataTable({
               processing: true,
               serverSide: true,
               pageLength: 10,
               retrieve: true,
               bLengthChange: true,
               responsive: true,
               ajax: '{{route('bankList')}}',
               order: [3, 'desc'],
               autoWidth: false,
               language: {
                        paginate: {
                            next: 'Next &#8250;',
                            previous: '&#8249; Previous'
                        }
                    },
               columns: [
                   {"data": "bank_name","orderable": false},
                   {"data": "account_holder_name","orderable": false},
                   {"data": "iban","orderable": false},
                   {"data": "country","orderable": false},
                   {"data": "created_at","orderable": false},
                   {"data": "status","orderable": false},
                   {"data": "activity","orderable": false}
               ],
           });

    </script>

@endsection