@include('email.header_new')

<p>Hello, <?php echo $data->first_name.' '.$data->last_name; ?></p>
<p>   Your {{allSetting()['app_title']}} email verification code is {{$key}}.
    <br>
</p>
<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{allSetting()['app_title']}}
</p>
@include('email.footer_new')