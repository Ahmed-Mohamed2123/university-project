<?php

// sanitizing for string
function sanitizeString($string)
{
    $string = trim($string);
    return filter_var($string,FILTER_SANITIZE_STRING);
}

// sanitizing for Integer
function sanitizeInteger($number) {
    return filter_var($number,FILTER_SANITIZE_NUMBER_INT);
}

// sanitize for email
function sanitizeEmail($email)
{
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}


// validate if empty
function checkEmpty($string)
{
    if(empty($string))
    {
        return false;
    }
    return true;
}

// validate for email
function validEmail($email)
{
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return false;
    }
    return true;
}

