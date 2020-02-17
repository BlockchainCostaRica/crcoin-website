@extends('backend.admin-master',['menu'=>'user'])
@section('title',__('User Details'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('User Account')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <!-- user-details -->
                        <div class="user-details useraccount-wrap">
                            <h3 class="user-title">{{__('User Account Details')}}</h3>
                            <ul>
                                <li>{{__('Full Name')}} <span class="dotted-style">:</span> <span class="text-style">{{$account_data['userName']}}</span></li>
                                <li>{{__('Email Address')}} <span class="dotted-style">:</span> <span class="text-style">{{$account_data['userEmail']}}</span></li>
                                <li>{{__('Current Wallet Balance')}} <span class="dotted-style">:</span> <span class="text-style">{{$account_data['balance']}}</span></li>
                                <li>{{__('Total Deposits')}} <span class="dotted-style">:</span> <span class="text-style">{{$account_data['deposits']}}</span></li>
                                <li>{{__('Total Withdrawals')}} <span class="dotted-style">:</span> <span class="text-style">{{$account_data['withdrawals']}}</span></li>
                                <li class="extrabalance">{{__('Extra Balance')}} <span class="dotted-style">:</span> <span class="text-style">{{$account_data['extra_balance']}} @if($account_data['extra_balance']<0)<span>(Suspicious User)</span> @endif </span></li>
                            </ul>
                        </div>
                        <!-- user-details -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')

@endsection