<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Helper;

class CommonFunctions
{
    static function getRandomString($length = 24, $chars = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789')
    {

        $result = '';
        $max = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            try {
                $result .= $chars[random_int(0, $max - 1)];
            } catch (\Exception $e) {
                $result = $chars[0];
            }
        }

        return $result;
    }
}
