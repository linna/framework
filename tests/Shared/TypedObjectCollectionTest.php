<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\Route;
use Linna\Shared\TypedObjectCollection;
use PHPUnit\Framework\TestCase;

class TypedObjectCollectionTest extends TestCase
{
    public function testCreateCollection()
    {
        $collection = new Linna\Shared\TypedObjectCollection(Route::class);

        $this->assertInstanceOf(TypedObjectCollection::class, $collection);
    }

    public function BadArgumentsProvider()
    {
        return [
            [null],
            [true],
            [1],
            [1.1],
            [[1]],
            [(object) [1]],
            [function () {
            }, function () {
            }],
        ];
    }

    /**
     * @dataProvider BadArgumentsProvider
     * @expectedException TypeError
     */
    public function testCreateCollectionWithBadArgument($args)
    {
        (new Linna\Shared\TypedObjectCollection($args));
    }

    public function testPopulateCollection()
    {
        $collection = new Linna\Shared\TypedObjectCollection(Route::class);

        $collection->push(
            new Linna\http\Route(['name' => 'route 1']),
            new Linna\http\Route(['name' => 'route 2']),
            new Linna\http\Route(['name' => 'route 3'])
        );

        $this->assertEquals(3, $collection->count());
    }

    /**
     * @dataProvider BadArgumentsProvider
     * @expectedException TypeError
     */
    public function testPopulateCollectionWithBadElements($element)
    {
        (new Linna\Shared\TypedObjectCollection(Route::class))->push($element);
    }

    public function testCollectionGetType()
    {
        $this->assertEquals(
            Route::class,
            (new Linna\Shared\TypedObjectCollection(Route::class))->getType()
        );
    }

    public function testCollectionToArray()
    {
        $collection = new Linna\Shared\TypedObjectCollection(Route::class);

        $array = [
            (new Linna\http\Route(['name' => 'route 1'])),
            (new Linna\http\Route(['name' => 'route 2'])),
            (new Linna\http\Route(['name' => 'route 3'])),
        ];

        $collection->push(
            new Linna\http\Route(['name' => 'route 1']),
            new Linna\http\Route(['name' => 'route 2']),
            new Linna\http\Route(['name' => 'route 3'])
        );

        $this->assertEquals($array, $collection->toArray());
    }
}
