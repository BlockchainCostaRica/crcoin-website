@extends('User.master',['menu'=>'refferal'])
@section('title', $title)
@section('content')
    <div class="refferal-page-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="refferal-page-inner">
                        <div class="invite-contact-area">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="refferal-title">
                                        <h3>{{__('Invite Your Contact')}}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-8 offset-lg-2">
                                    <div class="invite-contact-form">
                                        <div class="form-area">
                                            <form action="#">
                                                <div class="share-link-area">
                                                    <h4> <img src="{{asset('user')}}/assets/images/share.png" alt="share"> {{__('Share this link to your contact')}}</h4>
                                                    <div class="form-group">
                                                        <label for="url">{{__('Clicking this button will copy the URL to the user’s clipboard.')}}</label>
                                                        <input type="url" class="form-control" id="url" value="{{ $url }}" readonly>
                                                        <button onclick="CopyUrl()" type="button">{{__('Copy URL')}}</button>
                                                    </div>
                                                </div>
                                                <span class="or">or</span>
                                                <div class="share-code-area">
                                                    <h4> <img src="assets/images/share.png" alt="share"> {{__('Share On')}}</h4>
                                                    <div class="share-btn">
                                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{route('signup')}}?id={{encrypt(\Illuminate\Support\Facades\Auth::id())}}" target="_blank" class="facebook"><i class="fa fa-facebook"></i> Facebook</a>
                                                        <a target="_blank" href="http://www.twitter.com/share?url={{route('signup')}}?id={{encrypt(\Illuminate\Support\Facades\Auth::id())}}" class="twitter"><i class="fa fa-twitter"></i> {{__('Twitter')}}</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="all-travel-area">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="refferal-title">
                                        <h3>{{__('My References')}}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dt-responsive dc-table text-center">
                                        <thead>
                                        <tr>
                                            <th class="all">{{ __('Full Name') }}</th>
                                            <th class="desktop">{{ __('Email') }}</th>
                                            <th class="desktop">{{ __('Level') }}</th>
                                            <th class="desktop">{{ __('Joining Date') }}</th>
                                            <th class="all">{{ __('Balance') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($referrals))
                                            @foreach($referrals as $data)
                                                <tr>
                                                    <td>{{ $data['full_name'] }}</td>
                                                    <td class="email-case">{{ $data['email'] }}</td>
                                                    <td>{{ $data['level'] }}</td>
                                                    <td>{{ $data['joining_date'] }}</td>
                                                    <td>{{ user_balance($data['id']) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="all-travel-area">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="refferal-title">
                                        <h3>{{__('My Referrals')}}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="table-responsive">
                                        <table id="coin-summary-table" class="table  dataTable dt-responsive text-center" width="100%" role="grid">
                                        <thead>
                                        <tr role="row">
                                            @for($i = 1; $i <= $max_referral_level; $i++)
                                                <th class="referral-level" rowspan="1" colspan="1" aria-label="{{__('Level'). ' '. $i }}">
                                                    {{__('Level'). ' '. $i }}
                                                </th>
                                            @endfor
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="coin-summary-table-BTC-TCN" role="row" class="odd">
                                            @for($i = 1; $i <= $max_referral_level; $i++)
                                                <td>{{$referralLevel[$i]}}</td>
                                            @endfor
                                        </tr>
                                        <tr>
                                            <td colspan="{{$max_referral_level}}"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-earning-area">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="refferal-title">
                                        <h3>{{__('My Earnings')}}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-12 activity-list">
                                    <div class="table-responsive">
                                        <table class="table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{__('Period')}}</th>
                                                <th>{{__('Commissions')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @if(!empty($monthArray))

                                                @foreach($monthArray as $key=> $month)
                                                    <tr>
                                                        @php
                                                            $referral_bonus = isset($monthlyEarningHistories[$key]['total_amount']) ? $monthlyEarningHistories[$key]['total_amount'] : 0;
                                                        @endphp
                                                        <td>{{date('M Y', strtotime($key))}}</td>
                                                        <td>
                                                            {{ visual_number_format($referral_bonus) }}
                                                            {{settings('coin_name')}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center"><b>{{__('No Data available')}}</b></td>
                                                </tr>
                                            @endif
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
@endsection

@section('script')
    <script>
        function copy_clipboard() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            document.execCommand("Copy");
        }
    </script>
    <script>
        function CopyUrl() {

            /* Get the text field */
            var copyText = document.getElementById("url");

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");
            VanillaToasts.create({
                // title: 'Message Title',
                text: '{{__('URL copied successfully')}}',
                type: 'success',
                timeout: 3000

            });
        }
    </script>
    <script>
        $('#table-breakpoint').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            bLengthChange: false,
            responsive: true,
            ajax: '{{route('referralBonus')}}',
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "created_at"},
                {"data": "commission"},

            ]
        });
    </script>
@endsection