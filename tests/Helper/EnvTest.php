<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Helper\Env;
use PHPUnit\Framework\TestCase;

/**
 * Env Helper test.
 */
class EnvTest extends TestCase
{
    /**
     * Env values provider.
     *
     * @return array
     */
    public function envProvider() : array
    {
        return [
            ['FOO=foo', 'FOO', 'foo'],
            ['FOO=true', 'FOO', true],
            ['FOO=(true)', 'FOO', true],
            ['FOO=false', 'FOO', false],
            ['FOO=(false)', 'FOO', false],
            ['FOO=empty', 'FOO', ''],
            ['FOO=(empty)', 'FOO', ''],
            ['FOO=null', 'FOO', null],
            ['FOO=(null)', 'FOO', null],
            ['FOO="foo"', 'FOO', 'foo'],
            ['FOO="fo"', 'FOO', 'fo'],
            ['FOO="f"', 'FOO', 'f'],
            ['FOO="', 'FOO', '"'],
        ];
    }
    
    /**
     * Test env with.
     *
     * @dataProvider envProvider
     *
     * @param string $forPut
     * @param string $key
     * @param mixed $result
     */
    public function testEnv(string $forPut, string $key, $result)
    {
        putenv($forPut);
        $this->assertEquals($result, Env::get($key));
    }
    
    /**
     * Test env with default value.
     */
    public function testEnvWithDefaultValue()
    {
        putenv('FOO=foo');
        $this->assertEquals('bar', Env::get('BAR', 'bar'));
    }
}
