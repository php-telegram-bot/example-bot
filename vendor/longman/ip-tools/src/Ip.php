<?php
/*
 * This file is part of the IPTools package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\IPTools;

/**
 * Class to determine if an IP is located in a specific range as
 * specified via several alternative formats.
 *
 * @package    IPTools
 * @author     Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright  Avtandil Kikabidze <akalongman@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link       http://www.github.com/akalongman/php-ip-tools
 */
abstract class Ip
{
    protected static $ip;

    protected static $isv6 = false;


    /**
     * Checks if an IP is valid.
     *
     * @param string $ip IP
     * @return boolean true if IP is valid, otherwise false.
     */
    public static function isValid($ip)
    {
        $valid = self::isValidv4($ip);
        if ($valid) {
            self::$ip = $ip;
            self::$isv6 = false;
            return true;
        }

        $valid = self::isValidv6($ip);
        if ($valid) {
            self::$ip = $ip;
            self::$isv6 = true;
            return true;
        }
        return false;
    }


    /**
     * Checks if an IP is valid IPv4 format.
     *
     * @param string $ip IP
     * @return boolean true if IP is valid IPv4, otherwise false.
     */
    public static function isValidv4($ip)
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    /**
     * Checks if an IP is valid IPv6 format.
     *
     * @param string $ip IP
     * @return boolean true if IP is valid IPv6, otherwise false.
     */
    public static function isValidv6($ip)
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * Checks if an IP is local
     *
     * @param  string  $ip IP
     * @return boolean     true if the IP is local, otherwise false
     */
    public static function isLocal($ip)
    {
        $localIpv4Ranges = array(
            '10.*.*.*',
            '127.*.*.*',
            '192.168.*.*',
            '169.254.*.*',
            '172.16.0.0-172.31.255.255',
            '224.*.*.*',
        );

        $localIpv6Ranges = array(
            'fe80::/10',
            '::1/128',
            'fc00::/7'
        );

        if (self::isValidv4($ip)) {
            return self::match($ip, $localIpv4Ranges);
        }

        if (self::isValidv6($ip)) {
            return self::match($ip, $localIpv6Ranges);
        }

        return false;
    }

    /**
     * Checks if an IP is remot
     *
     * @param  string  $ip IP
     * @return boolean     true if the IP is remote, otherwise false
     */
    public static function isRemote($ip)
    {
        return !self::isLocal($ip);
    }

    /**
     * Checks if an IP is part of an IP range.
     *
     * @param string $ip IPv4/IPv6
     * @param mixed $range IP range specified in one of the following formats:
     * Wildcard format:     1.2.3.*
     * CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
     * Start-End IP format: 1.2.3.0-1.2.3.255
     * @return boolean true if IP is part of range, otherwise false.
     */
    public static function match($ip, $ranges)
    {
        if (is_array($ranges)) {
            foreach ($ranges as $range) {
                $match = self::compare($ip, $range);
                if ($match) {
                    return true;
                }
            }
        } else {
            return self::compare($ip, $ranges);
        }
        return false;
    }

    /**
     * Checks if an IP is part of an IP range.
     *
     * @param string $ip IPv4/IPv6
     * @param string $range IP range specified in one of the following formats:
     * Wildcard format:     1.2.3.* OR 2001:cdba:0000:0000:0000:0000:3257:*
     * CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
     * Start-End IP format: 1.2.3.0-1.2.3.255 OR 2001:cdba:0000:0000:0000:0000:3257:0001-2001:cdba:0000:0000:0000:0000:3257:1000
     * @return boolean true if IP is part of range, otherwise false.
     */
    public static function compare($ip, $range)
    {
        if (!self::isValid($ip)) {
            throw new \InvalidArgumentException('Input IP "'.$ip.'" is invalid!');
        }

        $status = false;
        if (strpos($range, '/') !== false) {
            $status = self::processWithSlash($range);
        } else if (strpos($range, '*') !== false) {
            $status = self::processWithAsterisk($range);
        } else if (strpos($range, '-') !== false) {
            $status = self::processWithMinus($range);
        } else {
            $status = ($ip === $range);
        }
        return $status;
    }


    /**
     * Checks if an IP is part of an IP range.
     *
     * @param string $range IP range specified in one of the following formats:
     * CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
     * @return boolean true if IP is part of range, otherwise false.
     */
    protected static function processWithSlash($range)
    {
        list($range, $netmask) = explode('/', $range, 2);

        if (self::$isv6) {
            if (strpos($netmask, ':') !== false) {
                $netmask     = str_replace('*', '0', $netmask);
                $netmask_dec = self::ip2long($netmask);
                return ((self::ip2long(self::$ip) & $netmask_dec) == (self::ip2long($range) & $netmask_dec));
            } else {
                $x = explode(':', $range);
                while (count($x) < 8) {
                    $x[] = '0';
                }

                list($a, $b, $c, $d, $e, $f, $g, $h) = $x;
                $range = sprintf(
                    "%u:%u:%u:%u:%u:%u:%u:%u",
                    empty($a) ? '0' : $a,
                    empty($b) ? '0' : $b,
                    empty($c) ? '0' : $c,
                    empty($d) ? '0' : $d,
                    empty($e) ? '0' : $e,
                    empty($f) ? '0' : $f,
                    empty($g) ? '0' : $g,
                    empty($h) ? '0' : $h
                );
                $range_dec           = self::ip2long($range);
                $ip_dec              = self::ip2long(self::$ip);
                $wildcard_dec        = pow(2, (32 - $netmask)) - 1;
                $netmask_dec         = ~$wildcard_dec;

                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        } else {
            if (strpos($netmask, '.') !== false) {
                $netmask     = str_replace('*', '0', $netmask);
                $netmask_dec = self::ip2long($netmask);
                return ((self::ip2long(self::$ip) & $netmask_dec) == (self::ip2long($range) & $netmask_dec));
            } else {
                $x = explode('.', $range);
                while (count($x) < 4) {
                    $x[] = '0';
                }

                list($a, $b, $c, $d) = $x;
                $range               = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
                $range_dec           = self::ip2long($range);
                $ip_dec              = self::ip2long(self::$ip);
                $wildcard_dec        = pow(2, (32 - $netmask)) - 1;
                $netmask_dec         = ~$wildcard_dec;

                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        }

        return false;
    }



    /**
     * Checks if an IP is part of an IP range.
     *
     * @param string $range IP range specified in one of the following formats:
     * Wildcard format:     1.2.3.* OR 2001:cdba:0000:0000:0000:0000:3257:*
     * @return boolean true if IP is part of range, otherwise false.
     */
    protected static function processWithAsterisk($range)
    {
        if (strpos($range, '*') !== false) {
            $lowerRange = self::$isv6 ? '0000' : '0';
            $upperRange = self::$isv6 ? 'ffff' : '255';

            $lower = str_replace('*', $lowerRange, $range);
            $upper = str_replace('*', $upperRange, $range);

            $range = $lower . '-' . $upper;
        }

        if (strpos($range, '-') !== false) {
            return self::processWithMinus($range);
        }

        return false;
    }

    /**
     * Checks if an IP is part of an IP range.
     *
     * @param string $range IP range specified in one of the following formats:
     * Start-End IP format: 1.2.3.0-1.2.3.255 OR 2001:cdba:0000:0000:0000:0000:3257:0001-2001:cdba:0000:0000:0000:0000:3257:1000
     * @return boolean true if IP is part of range, otherwise false.
     */
    protected static function processWithMinus($range)
    {
        list($lower, $upper) = explode('-', $range, 2);
        $lower_dec           = self::ip2long($lower);
        $upper_dec           = self::ip2long($upper);
        $ip_dec              = self::ip2long(self::$ip);

        return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
    }


    /**
     * Gets IP long representation
     *
     * @param string $ip IPv4 or IPv6
     * @return long If IP is valid returns IP long representation, otherwise -1.
     */
    public static function ip2long($ip)
    {
        $long = -1;
        if (self::isValidv6($ip)) {
            if (!function_exists('bcadd')) {
                throw new \RuntimeException('BCMATH extension not installed!');
            }

            $ip_n = inet_pton($ip);
            $bin = '';
            for ($bit = strlen($ip_n) - 1; $bit >= 0; $bit--) {
                $bin = sprintf('%08b', ord($ip_n[$bit])) . $bin;
            }

            $dec = '0';
            for ($i = 0; $i < strlen($bin); $i++) {
                $dec = bcmul($dec, '2', 0);
                $dec = bcadd($dec, $bin[$i], 0);
            }
            $long = $dec;
        } else if (self::isValidv4($ip)) {
            $long = ip2long($ip);
        }
        return $long;
    }


    /**
     * Gets IP string representation from IP long
     *
     * @param long $dec IPv4 or IPv6 long
     * @return string If IP is valid returns IP string representation, otherwise ''.
     */
    public static function long2ip($dec, $ipv6 = false)
    {
        $ipstr = '';
        if ($ipv6) {
            if (!function_exists('bcadd')) {
                throw new \RuntimeException('BCMATH extension not installed!');
            }

            $bin = '';
            do {
                $bin = bcmod($dec, '2') . $bin;
                $dec = bcdiv($dec, '2', 0);
            } while (bccomp($dec, '0'));

            $bin = str_pad($bin, 128, '0', STR_PAD_LEFT);
            $ip = array();
            for ($bit = 0; $bit <= 7; $bit++) {
                $bin_part = substr($bin, $bit * 16, 16);
                $ip[] = dechex(bindec($bin_part));
            }
            $ip = implode(':', $ip);
            $ipstr = inet_ntop(inet_pton($ip));
        } else {
            $ipstr = long2ip($dec);
        }
        return $ipstr;
    }

    public static function matchRange($ip, $range)
    {
        $ipParts = explode('.', $ip);
        $rangeParts = explode('.', $range);

        $ipParts = array_filter($ipParts);
        $rangeParts = array_filter($rangeParts);

        $ipParts = array_slice($ipParts, 0, count($rangeParts));

        return implode('.', $rangeParts) === implode('.', $ipParts);
    }
}
