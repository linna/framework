<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\Core_ext;

trait OptSystemTrait
{
    protected $opt;
    protected $optDefault;

    protected function optCheck($opt)
    {
        if ($this->optDefault === null) {
            throw new Exception('Options default must be declared for class ' . __CLASS__);
        }

        if (sizeof($opt) > 0) {
            $difFromDefault = $this->optCompare($opt, $this->optDefault);

            if (sizeof($difFromDefault) > 0) {
                throw new Exception("Invalid option passed to class " . __CLASS__);
            }

            $defaultReplaced = array_replace_recursive($this->optDefault, $opt);

            $this->opt = $defaultReplaced;
        } else {
            $this->opt = $this->optDefault;
        }
    }

    protected function optCompare($array1, $array2)
    {
        $aReturn = [];

        foreach ($array1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $array2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->optCompare($mValue, $array2[$mKey]);

                    if (count($aRecursiveDiff)) {
                        $aReturn[$mKey] = $aRecursiveDiff;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }
}
