<?php

/**
 * Return the default value of the given value.
 */
if(!function_exists('value')) {

    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

}

/**
 * get ip information from www.geoplugin.net
 */
if(!function_exists('ip_info')) {
    function ip_info($ip = null, $purpose = "location", $deep_detect = true)
    {
        $output = null;
        if(filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if($deep_detect) {
                if(filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                if(filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
        }
        $purpose = str_replace(["name", "\n", "\t", " ", "-", "_"], null, strtolower(trim($purpose)));
        $support = ["country", "countrycode", "state", "region", "city", "location", "address"];
        $continents = [
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        ];
        if(filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if(@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch($purpose) {
                    case "location":
                        $output = [
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        ];
                        break;
                    case "address":
                        $address = [$ipdat->geoplugin_countryName];
                        if(@strlen($ipdat->geoplugin_regionName) >= 1) {
                            $address[] = $ipdat->geoplugin_regionName;
                        }
                        if(@strlen($ipdat->geoplugin_city) >= 1) {
                            $address[] = $ipdat->geoplugin_city;
                        }
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }

        return $output;
    }
}

if(!function_exists('di')) {

    function di($service = null)
    {
        return is_null($service) ? \Phalcon\Di::getDefault() : \Phalcon\DI::getDefault()->get($service);
    }

}

if(!function_exists('base_path')) {

    function base_path($path = null)
    {
        if(!di()->has('basePath')) return ROOT_PATH . DIRECTORY_SEPARATOR . $path;

        $base = di('basePath');

        return is_null($path) ? $base : $base . DIRECTORY_SEPARATOR . $path;
    }

}

if(!function_exists('forward')) {

    function forward(array $options)
    {
        return di('dispatcher')->forward($options);
    }
}

if(!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if(!function_exists('env')) {

    function env($key, $default = null)
    {
        $value = getenv($key);

        if(false === $value) return value($default);

        switch(strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'null':
            case '(null)':
                return null;

            case 'empty':
            case '(empty)':
                return '';
        }

        return $value;
    }

}
