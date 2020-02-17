@extends('User.master',['menu'=>'my_wallet'])
@section('content')
<div class="chart-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="chart-list">
                    <div class="chart-inner">
                        <div class="chart-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-titile">
                                        <h3>{{__('Wallet Transaction Activity')}}</h3>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-btn text-right">
                                        <a href="#" data-popup-open="wallet-popup" class="primary-btn ">{{__('Add wallet')}}</a>
                                        <a href="#" data-popup-open="coin-popup" class="primary-btn color">{{__('Move Coin')}}</a>
                                        <!-- wallet-popup start here -->
                                        <div class="popup" data-popup="wallet-popup">
                                            <div class="popup-inner">
                                                <div class="popup-title">
                                                    <h3>{{__('Add Wallet')}}</h3>
                                                </div>
                                                <div class="popup-content">
                                                    <div class="form-area">
                                                        <form method="post" action="{{route('createWallet')}}" id="walletCreateForm">
                                                            @csrf
                                                            <div class="form-group">
                                                                <div class="alert alert-danger d-none error_msg" id="" role="alert"></div>
                                                                <div class="alert alert-success d-none succ_msg" id="" role="alert"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="name">{{__('Name')}}</label>
                                                                <input name="wallet_name" type="text" class="form-control" id="name" placeholder="{{__('Write wallet name')}}">
                                                            </div>
                                                            <button type="button" id="walletCreate" class="primary-btn">{{__('Add')}}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <a class="popup-close" data-popup-close="wallet-popup" href="#">x</a>
                                            </div>
                                        </div>
                                        <!-- wallet-popup end here -->
                                        <!-- coin-popup start here -->
                                        <div class="popup" data-popup="coin-popup">
                                            <div class="popup-inner">
                                                <div class="popup-title">
                                                    <h3>Move Coin</h3>
                                                </div>
                                                <div class="popup-content">
                                                    <div class="form-area">
                                                        <form action="#">
                                                            <div class="form-group">
                                                                <label>From</label>
                                                                <select class="wide">
                                                                    <option data-display="Select sender account">Select receiver account</option>
                                                                    <option value="1">Some option</option>
                                                                    <option value="2">Another option</option>
                                                                    <option value="4">Potato</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>To</label>
                                                                <select class="wide">
                                                                    <option data-display="Select receiver account">Select receiver account</option>
                                                                    <option value="1">Some option</option>
                                                                    <option value="2">Another option</option>
                                                                    <option value="4">Potato</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amount">Amount</label>
                                                                <input type="text" class="form-control" id="amount" placeholder="0.000000000">
                                                            </div>
                                                            <button type="submit" class="primary-btn">Move</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <a class="popup-close" data-popup-close="coin-popup" href="#">x</a>
                                            </div>
                                        </div>
                                        <!-- coin-popup end here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-info">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table id="table-breakpoint">
                                            <thead>
                                            <tr>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Balance')}}</th>
                                                <th>{{__('Updated At')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                               @foreach($wallets as $wallet)
                                                <tr>
                                                    <td>{{$wallet->name}}</td>
                                                    <td>{{$wallet->balance}}</td>
                                                    <td> <p>{{date('Y-m-d',strtotime($wallet->updated_at))}}      <span>{{date('H:i:s',strtotime($wallet->updated_at))}}</span></p> </td>
                                                    <td>
                                                        <div class="action-icon">
                                                            <a href="{{route('walletDetails',$wallet->id)}}?q=deposite" class="wallet"><i class="flaticon-wallet"></i></a>
                                                            <a href="{{route('walletDetails',$wallet->id)}}?q=activity" class="share"><i class="flaticon-share"></i></a>
                                                            <a href="{{route('walletDetails',$wallet->id)}}?q=withdraw" class="send"><i class="flaticon-send"></i></a>
                                                            @if($wallet->is_primary == 0)
                                                            <a href="{{route('makeDefaultAccount',$wallet->id)}}" class="send"><i class="fa fa-key"></i></a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                               @endforeach
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
@endsection
@section('script')
    <script>
        // createWallet
        $(function () {
            $(document.body).on('click','#walletCreate', function(e){
                $('.error_msg').addClass('d-none');
                $('.succ_msg').addClass('d-none');
                var form = $('#walletCreateForm');
                $.ajax({
                    type: "GET",
                    enctype: 'multipart/form-data',
                    url: form.attr('action'),
                    data: $('#walletCreateForm').serialize(),
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true){

                            $('#walletCreateForm').submit();
                        } else {
                            e.preventDefault();
                            $('.error_msg').removeClass('d-none');
                            $('.error_msg').html(data.message);

                        }
                    }
                });
                return false;
            });
        });
    </script>
    @endsection