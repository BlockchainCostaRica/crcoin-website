@extends('landing-master')
@section('title',__('Landing'))
@section('content')

<div class="page-wrapper">
    <!-- breadcumb-area start -->
    <div class="breadcumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>{{__('FAQS')}}</h2>
                    <ul>
                        <li><a href="{{route('home')}}">{{__('Home')}}</a></li>
                        <li>{{__('Faq')}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcumb-area end -->
    <!-- faq-area start -->
    <div class="faq-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="faq-wrapper">
                        <div class="accordion" id="accordion">
                            @if(isset($item[0]))
                                @for($i=0;$i<count($item);$i++)
                                    <div class="card">
                                        <div class="card-header" id="head_{{$i}}">
                                            <h5 data-toggle="collapse" data-target="#collapse{{$i}}" aria-expanded="@if($i==0)true @else false @endif" aria-controls="collapseOne" @if($i!=0)class="collapsed" @endif>{{$i+1}}. {{$item[$i]->question}}</h5>
                                        </div>
                                        <div id="collapse{{$i}}" class="collapse @if($i==0)show @endif" aria-labelledby="head_{{$i}}" data-parent="#accordion">
                                            <div class="card-body">
                                                {!! $item[$i]->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- faq-area end -->
@endsection
