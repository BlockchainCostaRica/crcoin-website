<?php

use App\Model\Admin\AdminSetting;
use App\Model\Admin\CustomPages;
use Illuminate\Database\Seeder;

class AdminSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminSetting::insert(['slug'=>'coin_price','value'=>'2.50']);
        AdminSetting::insert(['slug'=>'coin_name','value'=>'BitCR']);
        AdminSetting::insert(['slug'=>'app_title','value'=>'BitCR wallet']);
        AdminSetting::insert(['slug'=>'maximum_withdrawal_daily','value'=>'3']);
        AdminSetting::insert(['slug'=>'mail_from','value'=>'noreply@bitcr.co']);
        AdminSetting::insert(['slug'=>'admin_coin_address','value'=>'address']);

        AdminSetting::create(['slug' => 'maintenance_mode', 'value' => 'no']);
        AdminSetting::create(['slug' => 'logo', 'value' => '']);
        AdminSetting::create(['slug' => 'login_logo', 'value' => '']);
        AdminSetting::create(['slug' => 'landing_logo', 'value' => '']);
        AdminSetting::create(['slug' => 'favicon', 'value' => '']);
        AdminSetting::create(['slug' => 'copyright_text', 'value' => 'Powered by Blockchain Costa Rica']);
        AdminSetting::create(['slug' => 'pagination_count', 'value' => '10']);
        AdminSetting::create(['slug' => 'point_rate', 'value' => '1']);
        //General Settings
        AdminSetting::create(['slug' => 'lang', 'value' => 'en']);
        AdminSetting::create(['slug' => 'company_name', 'value' => 'Test Company']);
        AdminSetting::create(['slug' => 'primary_email', 'value' => 'test@email.com']);

        AdminSetting::create(['slug' => 'twilo_id', 'value' => 'test']);
        AdminSetting::create(['slug' => 'twilo_token', 'value' => 'test']);
        AdminSetting::create(['slug' => 'sender_phone_no', 'value' => 'test']);
        AdminSetting::create(['slug' => 'ssl_verify', 'value' => '']);


        AdminSetting::create(['slug' => 'braintree_client_token', 'value' => 'test']);
        AdminSetting::create(['slug' => 'braintree_environment', 'value' => 'sandbox']);
        AdminSetting::create(['slug' => 'braintree_merchant_id', 'value' => 'test']);
        AdminSetting::create(['slug' => 'braintree_public_key', 'value' => 'test']);
        AdminSetting::create(['slug' => 'braintree_private_key', 'value' => 'test']);
        AdminSetting::create(['slug' => 'sms_getway_name', 'value' => 'twillo']);
        AdminSetting::create(['slug' => 'clickatell_api_key', 'value' => 'test']);
        AdminSetting::create(['slug' => 'number_of_confirmation', 'value' => '6']);
        AdminSetting::create(['slug' => 'referral_commission_percentage', 'value' => '10']);


        // Coin Api
        AdminSetting::create(['slug' => 'coin_api_user', 'value' => 'test']);
        AdminSetting::create(['slug' => 'coin_api_pass', 'value' => 'test']);
        AdminSetting::create(['slug' => 'coin_api_host', 'value' => 'test5']);
        AdminSetting::create(['slug' => 'coin_api_port', 'value' => 'test']);

        AdminSetting::create(['slug' => 'btc_coin_api_user', 'value' => 'test']);
        AdminSetting::create(['slug' => 'btc_coin_api_pass', 'value' => 'test']);
        AdminSetting::create(['slug' => 'btc_coin_api_host', 'value' => 'test']);
        AdminSetting::create(['slug' => 'btc_coin_api_port', 'value' => 'test']);


        // Send Fees
        AdminSetting::create(['slug' => 'send_fees_type', 'value' => 1]);
        AdminSetting::create(['slug' => 'send_fees_fixed', 'value' => 0]);
        AdminSetting::create(['slug' => 'send_fees_percentage', 'value' => 0]);
        AdminSetting::create(['slug' => 'max_send_limit', 'value' => 0]);
        //order settings
        AdminSetting::create(['slug' => 'coin_price', 'value' => 1]);
        AdminSetting::create(['slug' => 'deposit_time', 'value' => 1]);

        // Landing Pages

        AdminSetting::create(['slug' => 'landing_title', 'value' =>'The Highly Secured Bitcoin Wallet']);
        AdminSetting::create(['slug' => 'landing_description', 'value' =>'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.There are many There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.There are many.Contact Us']);
        AdminSetting::create(['slug' => 'landing_button_link', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_ios_app_link', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_and_app_link', 'value' =>'']);
        /////////////////////////////
        AdminSetting::create(['slug' => 'about_us_title', 'value' =>'Who We Are']);
        AdminSetting::create(['slug' => 'about_us_sub_title', 'value' =>'There are many variations of passages of Lorem Ipsum available, but the majority ']);
        AdminSetting::create(['slug' => 'user_panel_details', 'value' =>'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop.']);
        AdminSetting::create(['slug' => 'admin_panel_details', 'value' =>'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here.']);
        AdminSetting::create(['slug' => 'app_details', 'value' =>'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here.']);
        AdminSetting::create(['slug' => 'about_us_logo', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_integration_title', 'value' =>'Easy Customization & Secure Payment System.']);
        AdminSetting::create(['slug' => 'landing_integration_button_link', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_integration_description', 'value' =>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy when Lorem Ipsum is simply dummy text of the printing and typesetting industry I completely follow all your instructions.']);
        AdminSetting::create(['slug' => 'landing_integration_page_logo', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_integration_2nd_title', 'value' =>'Interactive And Awesome Refferal System']);
        AdminSetting::create(['slug' => 'landing_integration_2nd_button_link', 'value' =>route('signup')]);
        AdminSetting::create(['slug' => 'landing_integration_2nd_description', 'value' =>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy when Lorem Ipsum is simply dummy text of the printing and typesetting industry I completely follow all your instructions.']);
        AdminSetting::create(['slug' => 'landing_page_2nd_logo', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_feature_title', 'value' =>'Our Awesome Feature']);
        AdminSetting::create(['slug' => 'landing_feature_subtitle', 'value' =>'There are many variations of passages of Lorem Ipsum available, but the majority']);
        AdminSetting::create(['slug' => '1st_feature_title', 'value' =>'Easy Customize']);
        AdminSetting::create(['slug' => '1st_feature_subtitle', 'value' =>'The point of using Lorem Ipsum is that it has or-less normal distribution letters, as opposed Content here.']);
        AdminSetting::create(['slug' => '1st_feature_icon', 'value' =>'']);
        AdminSetting::create(['slug' => '2nd_feature_title', 'value' =>'Awesome Design']);
        AdminSetting::create(['slug' => '2nd_feature_subtitle', 'value' =>'The point of using Lorem Ipsum is that it has or-less normal distribution letters, as opposed Content here.']);
        AdminSetting::create(['slug' => '3rd_feature_subtitle', 'value' =>'The point of using Lorem Ipsum is that it has or-less normal distribution letters, as opposed Content here.']);
        AdminSetting::create(['slug' => '2nd_feature_icon', 'value' =>'']);
        AdminSetting::create(['slug' => '3rd_feature_title', 'value' =>'Extreme Security']);
        AdminSetting::create(['slug' => '3rd_feature_icon', 'value' =>'']);
        AdminSetting::create(['slug' => 'landing_screenshot_title', 'value' =>'Product screenshot']);
        AdminSetting::create(['slug' => 'landing_screenshot_subtitle', 'value' =>'There are many variations of passages of Lorem Ipsum available, but the majority']);
        AdminSetting::create(['slug' => 'admin_1st_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'admin_2nd_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'admin_3rd_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'user_1st_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'user_2nd_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'users_3rd_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'app_1st_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'app_2nd_screenshot', 'value' =>'']);
        AdminSetting::create(['slug' => 'app_3rd_screenshot', 'value' =>'']);

        CustomPages::create(['title' => 'What is Lorem Ipsum?', 'key' =>'Terms and conditions','description'=>'']);
    }
}
