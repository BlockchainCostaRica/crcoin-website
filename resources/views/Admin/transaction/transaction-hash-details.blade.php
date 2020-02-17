@extends('Admin.master',['menu'=>'transactions','submenu'=>'transaction_hash'])
@section('title',__('Node History'))
@section('content')
    <div class="user-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page " >{{settings('app_title')}}</li>
                                <li class="page active"> Node history</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-inner section-height">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- from area start here -->
                            <div class="form-area plr-65 pt-45">
                                <form action="{{route('adminTransactionHashDetails')}}"  method="POST" />
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transaction">Node Transaction By Hash</label>
                                                <input name="hash" type="text" class="form-control" id="transaction"  placeholder="Transaction Hash">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="button-primary ">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- from area start here -->
                        </div>
                    </div>
                    @if(isset($response))
                        <pre>
                            {{ var_export($response) }}
                        </pre>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
@endsection