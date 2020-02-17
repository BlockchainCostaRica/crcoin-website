@include('email.header_new')
<p>Hi {{(!empty(\App\User::where('email',$email)->first())) ? \App\User::where('email',$email)->first()->first_name.' '.\App\User::where('email',$email)->first()->last_name : ' '}},</p>
<p>

    {!! $cause !!}
</p>
<p>
    <b style="text-color:red">ID Rejected</b> <br/>
    Please upload again
</p>
<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{allSetting()['app_title']}}
</p>
@include('email.footer_new')