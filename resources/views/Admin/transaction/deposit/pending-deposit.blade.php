@extends('Admin.master',['menu'=>'pending_transaction'])
@section('title',__('Buy Coin Order'))
@section('content')
    <div class="orderlist-page">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page active " >{{__('Buy Coin Order List')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-inner pb-45">
                    <!-- table-area start here -->
                    <div class="table-area">
                        <div class="table-responsive">
                            <table class="table" id="deposittable">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Email')}}</th>
                                    <th scope="col">{{__('Coin amount')}}</th>
                                    <th scope="col">{{__('Payment Type')}}</th>
                                    <th scope="col">{{__('Date')}}</th>
                                    <th scope="col">{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- reject start here -->
                    <div class="popup" data-popup="bank-deposit">
                        <div class="popup-inner">
                            <div class="popup-title">
                                <h3>Card Name</h3>
                            </div>
                            <div class="popup-content">
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td>Card Holder Name :</td>
                                                <td>Alfred John</td>
                                            </tr>
                                            <tr>
                                                <td>Card Number :</td>
                                                <td>0987 5678 9876 1234</td>
                                            </tr>
                                            <tr>
                                                <td>Expaire Date :</td>
                                                <td>02 - 12- 2022</td>
                                            </tr>
                                            <tr>
                                                <td>CVV Number :</td>
                                                <td>*******</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <a class="popup-close" data-popup-close="bank-deposit" href="#">x</a>
                        </div>
                    </div>
                    <!-- reject end here -->
                    <!-- reject start here -->
                    <div class="popup" data-popup="craditcrid">
                        <div class="popup-inner">
                            <div class="popup-title">
                                <h3>Card Name</h3>
                            </div>
                            <div class="popup-content">
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td>Card Holder Name :</td>
                                                <td>Alfred John</td>
                                            </tr>
                                            <tr>
                                                <td>Card Number :</td>
                                                <td>0987 5678 9876 1234</td>
                                            </tr>
                                            <tr>
                                                <td>Expaire Date :</td>
                                                <td>02 - 12- 2022</td>
                                            </tr>
                                            <tr>
                                                <td>CVV Number :</td>
                                                <td>*******</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <a class="popup-close" data-popup-close="craditcrid" href="#">x</a>
                        </div>
                    </div>
                    <!-- reject end here -->
                </div>
                <!-- pagination-area start here -->

                <!-- pagination-area start here -->
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection

@section('script')
    <script>
        $('#deposittable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            order:[4,'desc'],
            autoWidth:false,
            ajax: '{{route('adminPendingDeposit')}}',
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "email"},
                {"data": "coin"},
                {"data": "payment_type"},
                {"data": "date"},

                {"data": "action"}
            ]
        });
    </script>

@endsection