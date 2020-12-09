<?php

namespace App\Constants;

class AppConstants {

    const BUY = 1;
    const SELL = 2;
    
    const CASH_OUT = 1;
    const GOLD_OUT = 2;
    const GENERAL_REQUEST = 3;
    const CUSTOMER_QUESTION = 4;

    
    const MAIN_SELL_ITEM = 'GOLD24';
    const GENERAL_REQUEST_ID = 'GEN';
    const CUSTOMER_QUESTION_ID = 'QUE';
    
    const INTRUMENT_UNIT = 'Grams';
    
    const SMS_TOKEN_EXPIRY_TIME = 60;
    
    //customer requesr Statuses
    const PENDING = 1;
    const COMPLETE = 2;    
    
     //customer requesr limit
    const CUSTOMER_REQUEST_DISPLAY_LIMIT = 5;
    
    //Miscelaneous flags
     const CONTACT_US_FLAG = 1;
     const ABOUT_US_FLAG = 2;
    
}
