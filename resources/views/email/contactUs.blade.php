@include('email.header_new')
<p>From : {{$name}},</p>
<p>Subject : {{$subject}}</p>
<p>{{$details}}</p>
<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{allSetting()['app_title']}}
</p>
@include('email.footer_new')

