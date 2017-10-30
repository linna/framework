<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Helper\Str;
use PHPUnit\Framework\TestCase;

/**
 * Route test.
 */
class StrTest extends TestCase
{
    /**
     * Start one needle provider.
     *
     * @return array
     */
    public function startOneNeedleProvider() : array
    {
        return [
            ['=his is a string', ['='], true],
            ['t=is is a string', ['='], false],
            ['th=s is a string', ['='], false],
            ['thi= is a string', ['='], false],
            ['this=is a string', ['='], false],
            ['this =s a string', ['='], false],
            ['this i= a string', ['='], false],
            ['this is=a string', ['='], false],
            ['this is = string', ['='], false],
            ['this is a=string', ['='], false],
            ['this is a =tring', ['='], false],
            ['this is a s=ring', ['='], false],
            ['this is a st=ing', ['='], false],
            ['this is a str=ng', ['='], false],
            ['this is a stri=g', ['='], false],
            ['this is a strin=', ['='], false]
        ];
    }
    
    /**
     * Test startWith with one needle
     *
     * @dataProvider startOneNeedleProvider
     *
     * @param string $haystack
     * @param array $needles
     * @param bool $result
     */
    public function testStartsWithOneNeedle(string $haystack, array $needles, bool $result)
    {
        $this->assertEquals($result, Str::startsWith($haystack, $needles));
    }
    
    /**
     * Ends one needle provider.
     *
     * @return array
     */
    public function endsOneNeedleProvider() : array
    {
        return [
            ['=his is a string', ['='], false],
            ['t=is is a string', ['='], false],
            ['th=s is a string', ['='], false],
            ['thi= is a string', ['='], false],
            ['this=is a string', ['='], false],
            ['this =s a string', ['='], false],
            ['this i= a string', ['='], false],
            ['this is=a string', ['='], false],
            ['this is = string', ['='], false],
            ['this is a=string', ['='], false],
            ['this is a =tring', ['='], false],
            ['this is a s=ring', ['='], false],
            ['this is a st=ing', ['='], false],
            ['this is a str=ng', ['='], false],
            ['this is a stri=g', ['='], false],
            ['this is a strin=', ['='], true]
        ];
    }
    
    /**
     * Test endsWith with one needle
     *
     * @dataProvider endsOneNeedleProvider
     *
     * @param string $haystack
     * @param array $needles
     * @param bool $result
     */
    public function testEndsWithOneNeedle(string $haystack, array $needles, bool $result)
    {
        $this->assertEquals($result, Str::endsWith($haystack, $needles));
    }
    
    /**
     * Start some needle provider.
     *
     * @return array
     */
    public function startSomeNeedleProvider() : array
    {
        return [
            ['=his is a string', ['=','^'], true],
            ['t=is is a string', ['=','^'], false],
            ['th=s is a string', ['=','^'], false],
            ['thi= is a string', ['=','^'], false],
            ['this=is a string', ['=','^'], false],
            ['this =s a string', ['=','^'], false],
            ['this i= a string', ['=','^'], false],
            ['^his is=a string', ['=','^'], true],
            ['this is = string', ['=','^'], false],
            ['this is a=string', ['=','^'], false],
            ['this is a =tring', ['=','^'], false],
            ['this is a s=ring', ['=','^'], false],
            ['this is a st=ing', ['=','^'], false],
            ['this is a str=ng', ['=','^'], false],
            ['this is a stri=g', ['=','^'], false],
            ['this is a strin=', ['=','^'], false]
        ];
    }
    
    /**
     * Test startWith with some needle
     *
     * @dataProvider startSomeNeedleProvider
     *
     * @param string $haystack
     * @param array $needles
     * @param bool $result
     */
    public function testStartsWithSomeNeedle(string $haystack, array $needles, bool $result)
    {
        $this->assertEquals($result, Str::startsWith($haystack, $needles));
    }
    
    /**
     * Ends some needle provider.
     *
     * @return array
     */
    public function endsSomeNeedleProvider() : array
    {
        return [
            ['=his is a string', ['=','^'], false],
            ['t=is is a string', ['=','^'], false],
            ['th=s is a string', ['=','^'], false],
            ['thi= is a string', ['=','^'], false],
            ['this=is a string', ['=','^'], false],
            ['this =s a string', ['=','^'], false],
            ['this i= a string', ['=','^'], false],
            ['this is=a strin^', ['=','^'], true],
            ['this is = string', ['=','^'], false],
            ['this is a=string', ['=','^'], false],
            ['this is a =tring', ['=','^'], false],
            ['this is a s=ring', ['=','^'], false],
            ['this is a st=ing', ['=','^'], false],
            ['this is a str=ng', ['=','^'], false],
            ['this is a stri=g', ['=','^'], false],
            ['this is a strin=', ['=','^'], true]
        ];
    }
    
    /**
     * Test endsWith with some needle
     *
     * @dataProvider endsSomeNeedleProvider
     *
     * @param string $haystack
     * @param array $needles
     * @param bool $result
     */
    public function testEndsWithSomeNeedle(string $haystack, array $needles, bool $result)
    {
        $this->assertEquals($result, Str::endsWith($haystack, $needles));
    }
}
