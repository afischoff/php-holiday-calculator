<?php

include 'holidaycalc.class.php';

// namespace config
use \afischoff\HolidayCalc as HolidayCalc;

// load class and loop out future US Holidays in English
$usHolidays = new HolidayCalc('us_holidays', 'en_us');

// get an array of upcoming holidays in chronological order
$showUpcoming = true;
$unixTimeStampKeys = false; // when true, the next 2 params are ignored
$includeDateOutput = true;
$outputDateFormat = 'm/d/y';
$upcomingHolidays = $usHolidays->getAllHolidaysByCalendar($showUpcoming, $unixTimeStampKeys, $includeDateOutput, $outputDateFormat);

print_r($upcomingHolidays);

/*************************************/

// get an array of holidays in alphabetical order
$showUpcoming = true;
$unixTimeStampKeys = false; // when true, the next 2 params are ignored
$includeDateOutput = true;
$outputDateFormat = 'M d, Y';
$alphabeticHolidays = $usHolidays->getAllHolidaysByAlphabetic($showUpcoming, $unixTimeStampKeys, $includeDateOutput, $outputDateFormat);

print_r($alphabeticHolidays);

/*************************************/

// get an array of upcoming US and Jewish holidays together (in English), in chronological order
$usJewishHolidays = new HolidayCalc(array('us_holidays', 'jewish_holidays'), 'en_us');

$showUpcoming = true;
$unixTimeStampKeys = false; // when true, the next 2 params are ignored
$includeDateOutput = true;
$outputDateFormat = 'm/d/y';
$upcomingHolidays = $usJewishHolidays->getAllHolidaysByCalendar($showUpcoming, $unixTimeStampKeys, $includeDateOutput, $outputDateFormat);

print_r($upcomingHolidays);