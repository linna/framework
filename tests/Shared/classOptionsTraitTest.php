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
        $class = new FOOClassOP(['option1' => false,'option2' => false]);
        
        $options = $class->getCurrentOptions();
        
        $this->assertEquals(false, $options['option1']);
        $this->assertEquals(false, $options['option2']);
    }
}