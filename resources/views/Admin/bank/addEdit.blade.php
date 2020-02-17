@extends('Admin.master',['menu'=>'bank','submenu'=>'bank'])
@section('title',isset($item) ? 'Update Bank' : 'Add Bank')
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
                                <li class="page active">{{ $pageTitle }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- from area start here -->
                            <div class="form-area plr-65">
                                <form action="{{route('bankAddProcess')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname">{{__('Account Holder Name')}}</label>
                                                <input type="text" name="account_holder_name" class="form-control" id="firstname" placeholder="{{__('Holder name')}}"
                                                       @if(isset($item)) value="{{$item->account_holder_name}}" @else value="{{old('account_holder_name')}}" @endif>
                                                <span class="text-danger"><strong>{{ $errors->first('account_holder_name') }}</strong></span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastname">{{__('Account Holder Address')}}</label>
                                                <input name="account_holder_address" type="text" class="form-control" id="lastname"  placeholder="{{__('Account holder address')}}"
                                                       @if(isset($item)) value="{{$item->account_holder_address}}" @else value="{{old('account_holder_address')}}" @endif>
                                                <span class="text-danger"><strong>{{ $errors->first('account_holder_address') }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{{__('Bank Name')}}</label>
                                                <input type="text" name="bank_name" class="form-control" id="email" placeholder="{{__('Bank Name')}}"
                                                       @if(isset($item)) value="{{$item->bank_name}}" @else value="{{old('bank_name')}}" @endif>
                                                <span class="text-danger"><strong>{{ $errors->first('bank_name') }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank_address">{{__('Bank Address')}}</label>
                                                <input type="text" class="form-control" id="bank_address" name="bank_address"  placeholder="{{__('Bank Address')}}"
                                                       @if(isset($item)) value="{{$item->bank_address}}" @else value="{{old('bank_address')}}" @endif>
                                                <span class="text-danger"><strong>{{ $errors->first('bank_address') }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="iban">{{__('IBAN')}}</label>
                                                <input type="text" class="form-control" id="iban" name="iban"  placeholder="{{__('Bank Account Number')}}"
                                                       @if(isset($item)) value="{{$item->iban}}" @else value="{{old('iban')}}" @endif>
                                                <span class="text-danger"><strong>{{ $errors->first('iban') }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="swift_code">{{__('Swift Code')}}</label>
                                                <input type="text" class="form-control" id="swift_code" name="swift_code"  placeholder="{{__('Swift Code')}}"
                                                       @if(isset($item)) value="{{$item->swift_code}}" @else value="{{old('swift_code')}}" @endif>
                                                <span class="text-danger"><strong>{{ $errors->first('swift_code') }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('Country')}}</label>
                                                <select name="country" class="form-control wide">
                                                    <option value="">{{__('Select')}}</option>
                                                    @foreach(country() as $key => $value)
                                                        <option @if(isset($item) && ($item->country == $key)) selected
                                                                @elseif((old('country') != null) && (old('country') == $key)) @endif value="{{ $key }}">{{$value}}</option>
                                                        <span class="text-danger"><strong>{{ $errors->first('country') }}</strong></span>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('Activation Status')}}</label>
                                                <select name="status" class="form-control wide" >
                                                    @foreach(status() as $key => $value)
                                                        <option @if(isset($item) && ($item->status == $key)) selected
                                                                @elseif((old('status') != null) && (old('status') == $key)) @endif value="{{ $key }}">{{$value}}</option>
                                                        <span class="text-danger"><strong>{{ $errors->first('status') }}</strong></span>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{__('Short Note')}}</label>
                                                <textarea name="note" id="" rows="2" class="form-control">@if(isset($item)){{$item->note}}@else{{old('note')}}@endif</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            @if(isset($item))
                                                <input type="hidden" name="edit_id" value="{{$item->id}}">
                                            @endif
                                            <button class="button-primary ">@if(isset($item)) {{__('Update')}} @else {{__('Save')}} @endif</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
@endsection