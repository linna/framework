<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\FOO\FOOClassOP;
use PHPUnit\Framework\TestCase;

class classOptionsTraitTest extends TestCase
{
    public function testOverriddenOptions()
    {
        $opt = [
            'option1' => false,
            'option2' => false,
            'treeOption' => [
                0 => 1,
                1 => 2
                ]
            ];
        
        $class = new FOOClassOP($opt);
        
        $options = $class->getCurrentOptions();
        
        $this->assertEquals(false, $options['option1']);
        $this->assertEquals(false, $options['option2']);
        $this->assertEquals(1, $options['treeOption'][0]);
        $this->assertEquals(2, $options['treeOption'][1]);
    }
}
