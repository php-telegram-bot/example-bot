<?php
/*
 * This file is part of the IPTools package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Unit;

use \Longman\IPTools\Ip;

/**
 * @package        TelegramTest
 * @author         Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright      Avtandil Kikabidze <akalongman@gmail.com>
 * @license        http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link           http://www.github.com/akalongman/php-telegram-bot
 */
class IpTest extends TestCase
{

    /**
     * @test
     */
    public function test0()
    {
        $status = Ip::isValid('192.168.1.1');
        $this->assertTrue($status);

        $status = Ip::isValid('192.168.1.255');
        $this->assertTrue($status);

        $status = Ip::isValidv4('192.168.1.1');
        $this->assertTrue($status);

        $status = Ip::isValid('2001:0db8:85a3:08d3:1319:8a2e:0370:7334');
        $this->assertTrue($status);

        $status = Ip::isValidv4('2001:0db8:85a3:08d3:1319:8a2e:0370:7334');
        $this->assertFalse($status);

        $status = Ip::isValidv6('2001:0db8:85a3:08d3:1319:8a2e:0370:7334');
        $this->assertTrue($status);

        $status = Ip::isValid('192.168.1.256');
        $this->assertFalse($status);

        $status = Ip::isValid('2001:0db8:85a3:08d3:1319:8a2e:0370:733432');
        $this->assertFalse($status);


    }


    /**
     * @test
     */
    public function test1()
    {
        $status = Ip::match('192.168.1.1', '192.168.0.*');
        $this->assertFalse($status);

        $status = Ip::match('192.168.1.1', '192.168.0/24');
        $this->assertFalse($status);

        $status = Ip::match('192.168.1.1', '192.168.0.0/255.255.255.0');
        $this->assertFalse($status);

    }

    /**
     * @test
     */
    public function test2()
    {
        $status = Ip::match('192.168.1.1', '192.168.*.*');
        $this->assertTrue($status);

        $status = Ip::match('192.168.1.1', '192.168.1/24');
        $this->assertTrue($status);

        $status = Ip::match('192.168.1.1', '192.168.1.1/255.255.255.0');
        $this->assertTrue($status);
    }

    /**
     * @test
     */
    public function test3()
    {
        $status = Ip::match('192.168.1.1', '192.168.1.1');
        $this->assertTrue($status);
    }

    /**
     * @test
     */
    public function test4()
    {
        $status = Ip::match('192.168.1.1', '192.168.1.2');
        $this->assertFalse($status);
    }

    /**
     * @test
     */
    public function test5()
    {
        $status = Ip::match('192.168.1.1', array('192.168.123.*', '192.168.123.124'));
        $this->assertFalse($status);

        $status = Ip::match('192.168.1.1', array('122.128.123.123', '192.168.1.*', '192.168.123.124'));
        $this->assertTrue($status);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function test6()
    {
        $status = Ip::match('192.168.1.256', '192.168.1.2');
    }


    /**
     * @test
     */
    public function test7()
    {
        $status = Ip::match('192.168.5.5', '192.168.5.1-192.168.5.10');
        $this->assertTrue($status);

        $status = Ip::match('192.168.5.5', '192.168.1.1-192.168.10.10');
        $this->assertTrue($status);

        $status = Ip::match('192.168.5.5', '192.168.6.1-192.168.6.10');
        $this->assertFalse($status);
    }


    /**
     * @test
     */
    public function test8()
    {
        $status = Ip::match('2001:cdba:0000:0000:0000:0000:3257:9652', '2001:cdba:0000:0000:0000:0000:3257:*');
        $this->assertTrue($status);

        $status = Ip::match('2001:cdba:0000:0000:0000:0000:3257:9652', '2001:cdba:0000:0000:0000:0000:*:*');
        $this->assertTrue($status);

        $status = Ip::match('2001:cdba:0000:0000:0000:0000:3257:9652',
            '2001:cdba:0000:0000:0000:0000:3257:1234-2001:cdba:0000:0000:0000:0000:3257:9999');
        $this->assertTrue($status);


        $status = Ip::match('2001:cdba:0000:0000:0000:0000:3258:9652', '2001:cdba:0000:0000:0000:0000:3257:*');
        $this->assertFalse($status);


        $status = Ip::match('2001:cdba:0000:0000:0000:1234:3258:9652', '2001:cdba:0000:0000:0000:0000:*:*');
        $this->assertFalse($status);


        $status = Ip::match('2001:cdba:0000:0000:0000:0000:3257:7778',
            '2001:cdba:0000:0000:0000:0000:3257:1234-2001:cdba:0000:0000:0000:0000:3257:7777');
        $this->assertFalse($status);
    }


    /**
     * @test
     */
    public function test9()
    {

        $long = Ip::ip2long('192.168.1.1');
        $this->assertEquals('3232235777', $long);

        $dec = Ip::long2ip('3232235777');
        $this->assertEquals('192.168.1.1', $dec);

        $long = Ip::ip2long('fe80:0:0:0:202:b3ff:fe1e:8329');
        $this->assertEquals('338288524927261089654163772891438416681', $long);

        $dec = Ip::long2ip('338288524927261089654163772891438416681', true);
        $this->assertEquals('fe80::202:b3ff:fe1e:8329', $dec);
    }

    /**
     * @test
     */


    public function test_match_range()
    {
        $range = Ip::matchRange('192.168.100.', '192.168..');
        $this->assertTrue($range);

        $range = Ip::matchRange('192.168.1.200', '192.168.1.');
        $this->assertTrue($range);

        $range = Ip::matchRange('192.168.1.200', '192.168.2.');
        $this->assertFalse($range);
    }

    public function testLocal()
    {
        $status = Ip::isLocal('192.168.5.5');
        $this->assertTrue($status);

        $status = Ip::isLocal('fe80::202:b3ff:fe1e:8329');
        $this->assertTrue($status);
    }

    /**
     * @test
     */
    public function testRemote()
    {
        $status = Ip::isRemote('8.8.8.8');
        $this->assertTrue($status);
    }

}
