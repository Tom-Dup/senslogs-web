<?php

if (!function_exists('get_distance')) {
    function get_distance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}

if (!function_exists('is_local')) {
    function is_local()
    {
        return (env("APP_DEBUG", false) === true);
    }
}

if (!function_exists('is_true')) {
    function is_true($value)
    {
        if (!isset($value)) return false;
        if ($value === true) return true;
        if ($value === 1) return true;
        if ($value == "1") return true;
        if (strtolower($value) == "on") return true;
        if (strtolower($value) == "yes") return true;
        if (strtolower($value) == "true") return true;
        return false;
    }
}

if (!function_exists('is_date')) {
    function is_date($date)
    {
        return preg_match('/(\d{4})-(\d{2})-(\d{2})/', $date);
    }
}

if (!function_exists('is_datetime')) {
    function is_datetime($datetime)
    {
        return preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $datetime);
    }
}

