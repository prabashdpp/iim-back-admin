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

    const INSTRUMENT_UNIT = 'g';
    const INSTRUMENT_CURRENCY = '£';

    const SMS_TOKEN_EXPIRY_TIME = 60;

    //customer request Statuses
    const PENDING = 1;
    const COMPLETE = 2;

     //customer request limit
    const CUSTOMER_REQUEST_DISPLAY_LIMIT = 5;

    //Miscellaneous flags
     const CONTACT_US_FLAG = 1;
     const ABOUT_US_FLAG = 2;

     //gender
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_OTHER = 3;

    //request result statuses
    const REQUEST_RESULT_PENDING = 0;
    const REQUEST_RESULT_ACCEPTED= 1;
    const REQUEST_RESULT_REJECTED = 2;
    const REQUEST_RESULT_REPLIED = 3;
    const REQUEST_RESULT_RESOLVED = 4;
}
