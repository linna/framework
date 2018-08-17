<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\DotEnv\DotEnv;
use PHPUnit\Framework\TestCase;

/**
 * DotEnv test.
 */
class DotEnvTest extends TestCase
{
    /**
     * Test new instance.
     *
     */
    public function testNewInstance(): void
    {
        $this->assertInstanceOf(DotEnv::class, new DotEnv());
    }

    /**
     * Test load existing file.
     */
    public function testLoadFile(): void
    {
        $this->assertTrue((new DotEnv())->load(__DIR__.'/.env.test'));
    }

    /**
     * Test load non-existent file.
     */
    public function testLoadBadFile(): void
    {
        $this->assertFalse((new DotEnv())->load(__DIR__.'/.env.bad.test'));
    }

    /**
     * Env values provider.
     *
     * @return array
     */
    public function envProvider(): array
    {
        return [
            ['PHP_ENV', 'development'],
            ['BASIC', 'basic'],
            ['AFTER_LINE', 'after_line'],
            ['UNDEFINED_EXPAND', '$TOTALLY_UNDEFINED_ENV_KEY'],
            ['EMPTY', ''],
            ['SINGLE_QUOTES', 'single_quotes'],
            ['DOUBLE_QUOTES', 'double_quotes'],
            ['EXPAND_NEWLINES', 'expand\nnewlines'],
            ['DONT_EXPAND_NEWLINES_1', 'dontexpand\nnewlines'],
            ['DONT_EXPAND_NEWLINES_2', 'dontexpand\nnewlines'],
            ['EQUAL_SIGNS', 'equals'],
            ['RETAIN_INNER_QUOTES', '{"foo": "bar"}'],
            ['RETAIN_INNER_QUOTES_AS_STRING', '{"foo": "bar"}'],
            ['INCLUDE_SPACE', 'some spaced out string'],
            ['USERNAME', 'therealnerdybeast@example.tld']
         ];
    }

    /**
     * Test keys and values.
     *
     * @dataProvider envProvider
     *
     * @param string $key
     * @param string $value
     */
    public function testKeyValue(string $key, string $value): void
    {
        $d = new DotEnv();
        $d->load(__DIR__.'/.env.test');

        $this->assertSame($value, $d->get($key));
    }

    /**
     * Test default value.
     */
    public function testDefaultValue(): void
    {
        putenv('FOO=foo');
        $this->assertSame('bar', (new DotEnv())->get('BAR', 'bar'));
    }
}
