<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email template</title>

    <style>
        @media screen and (max-width:600px){
            #emailContainer{width: 100%;}
        }
        @media only screen and (max-width: 480px){
            td[class="email-content-box"],
            td[class="email-content-box"] table {
                display: block;
                width: 100%;
                text-align: left;
            }
            td[class="email-content-box-inner"],
            td[class="email-content-box-inner"] table td {
                padding: 15px 0 !important;
            }
            td[class="box-container"],
            td[class="box-container"] h2{
                font-size: 41px !important;
            }
            table[class="banner-text"] h2,
            table[id="emailBodysection2"] td div{
                font-size: 20px !important;
            }
            td[class="email-content-box-inner"] div,
            td[class="email-content-box-inner"] div p,
            td[class="email-content-box-inner"] div h3,
            td[class="email-content-box-inner"] div h4{
                font-size: 22px !important;
            }

        }
    </style>
</head>
<body bgcolor="#000" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

<!-- Start Email Container -->
<table border="0" cellpadding="0" cellspacing="0" id="emailContainer" style="max-width:600px;margin:0 auto;" bgcolor="#ffffff">
    <tr>
        <td align="center" valign="top" id="emailContainerCell">

            <!-- Start Email Header Area -->
            <table border="0" cellpadding="0" cellspacing="0" id="emailHeader" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;background: #fff;padding: 15px; background-color:#0E0E26;">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" mc:repeatable="header_logo" mc:variant="header_logo">
                            <tr>
                                <td valign="top">
                                    <div mc:edit="header_logo">
                                        <a href="#">
                                            <img @if(!empty(allSetting()['logo'])) src="{{asset(path_image().allSetting()['logo'])}}" @else src="{{asset('assets/images/logo.png')}}" @endif alt="" style="width:25%;">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="right" valign="center">
                        <table border="0" cellpadding="0" cellspacing="0" mc:repeatable="header_logo" mc:variant="header_logo">
                            <tr>
                                <td valign="center">
                                    <div mc:edit="header_logo">
                                        <p style="color: #999;">{{date('d M,Y')}}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Start Email Header Area -->
            <table border="0" cellpadding="0" cellspacing="0" id="emailHeader" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;background: #605ca8;padding: 15px;">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" mc:repeatable="banner" mc:variant="banner" style="text-align: center;">
                            <tr>
                                <td valign="top">
                                    <h2 style="margin-bottom: 0;font-size: 30px;color: #fff;">{{__('Welcome to')}} {{ allSetting()['app_title'] }}</h2>
                                    <p style="margin-top: 5px;color: #fff;font-size: 30px;">{{__('This is an important message for you')}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>