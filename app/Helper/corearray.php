<?php

//User Roles
function userRole($input = null)
{
    $output = [
        USER_ROLE_ADMIN => __('Admin'),
        USER_ROLE_USER => __('User')
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
//User Activity Array
function userActivity($input = null)
{
    $output = [
        USER_ACTIVITY_LOGIN => __('Log In'),
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
//Discount Type array
function discount_type($input = null)
{
    $output = [
        DISCOUNT_TYPE_FIXED => __('Fixed'),
        DISCOUNT_TYPE_PERCENTAGE => __('Percentage')
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

 function sendFeesType($input = null){
    $output = [
        DISCOUNT_TYPE_FIXED => __('Fixed'),
        DISCOUNT_TYPE_PERCENTAGE => __('Percentage')
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
function status($input = null)
{
    $output = [
        STATUS_ACTIVE => __('Active'),
        STATUS_DEACTIVE => __('Deactive'),
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
function deposit_status($input = null)
{
    $output = [
        STATUS_ACCEPTED => __('Accepted'),
        STATUS_PENDING => __('Pending'),
        STATUS_REJECTED => __('Rejected'),
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
function byCoinType($input = null)
{
    $output = [
        CARD => __('CARD'),
        BTC => __('BTC'),
        BANK_DEPOSIT => __('BANK DEPOSIT'),

    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function addressType($input = null){
    $output = [

        ADDRESS_TYPE_INTERNAL => __('Internal'),
        ADDRESS_TYPE_EXTERNAL => __('External'),
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}


function statusAction($input = null)
{
    $output = [
        STATUS_PENDING => __('Pending'),
        STATUS_SUCCESS => __('Active'),
        STATUS_REJECTED => __('Rejected'),
        //STATUS_FINISHED => __('Finished'),
        STATUS_SUSPENDED => __('Suspended'),
       // STATUS_REJECT => __('Rejected'),
        STATUS_DELETED => __('Deleted'),
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}


function actions($input = null)
{
    $output = [

    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
function days($input=null){
    $output = [
        DAY_OF_WEEK_MONDAY => __('Monday'),
        DAY_OF_WEEK_TUESDAY => __('Tuesday'),
        DAY_OF_WEEK_WEDNESDAY => __('Wednesday'),
        DAY_OF_WEEK_THURSDAY => __('Thursday'),
        DAY_OF_WEEK_FRIDAY => __('Friday'),
        DAY_OF_WEEK_SATURDAY => __('Saturday'),
        DAY_OF_WEEK_SUNDAY => __('Sunday')
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
function months($input=null){
    $output = [
        1 => __('January'),
        2 => __('February'),
        3 => __('Merch'),
        4 => __('April'),
        5 => __('May'),
        6 => __('June'),
        7 => __('July'),
        8 => __('August'),
        9 => __('September'),
        10 => __('October'),
        11 => __('November'),
        12 => __('December'),
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
function customPages($input=null){
    $output = [
        'faqs' => __('FAQS'),
        't_and_c' => __('T&C')
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}




/*********************************************
 *        End Ststus Functions
 *********************************************/
