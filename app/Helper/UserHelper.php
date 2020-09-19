<?php


namespace App\Helper;


class UserHelper
{

    public static function viewableUsers():?array {
        $userDomain = strtolower(substr(strrchr(auth()->user()->email, "@"), 1));
        $config = config('users.viewable');

        if($config === null) {
            return null;
        }
        $possible = [];
        foreach($config as $key => $value) {
            if(is_string($key) && ($key === '*' || strtolower($key) === $userDomain)) {
                if(is_array($value)) {
                    $possible = array_merge($possible, $value);
                } else if(sizeof($valueParts = explode(',', $value)) > 1) {
                    $possible = array_merge($possible, $valueParts);
                } else if($value === '*') {
                    return ['*'];
                } else if($value === $userDomain) {
                    $possible[] = $value;
                }
            } else {
                if($value === '*') {
                    return ['*'];
                } elseif(is_array($value) && in_array($userDomain, $value)) {
                    $possible = array_merge($possible, $value);
                } elseif(sizeof($valueParts = explode(',', $value)) > 1 && in_array($userDomain, $valueParts) ) {
                    $possible = array_merge($possible, $valueParts);
                } elseif($value === $userDomain) {
                    $possible[] = $value;
                }
            }
        }
        return $possible;
    }
}
