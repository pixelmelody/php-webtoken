<?php

/**
 *  Pixelmelody PHP WebToken - the easiest cloud token 
 *  
 *  @author      PixelMelody <lab@pixelmelody.com>
 *  @copyright   2015 Pixelmelody PT Portugal
 *  @link        http://www.pixelmelody.com/lab/phpwebtoken
 *  @license     http://www.pixelmelody.com/lab/phpwebtoken
 *  @package     phpwebtoken
 *  
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *  
 *  http://www.apache.org/licenses/LICENSE-2.0
 *  
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace pixelmelody\phpwebtoken\contracts;

/**
 * FormatAbstract
 *
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
abstract class FormatAbstract implements FormatInterface {

    /**
     * Data array to manage
     * @var array
     */
    private $values;

    /**
     * Construtor em FormatAbstract
     */
    function __construct($contentarray = []) {
        $this->values = [];
        if (is_array($contentarray) && count($contentarray) > 0) {
            foreach ($contentarray as $k => $v) {
                $e = explode('=', $v);
                if (count($e) === 2) {
                    $this->values[strtolower($e[0])] = $e[1];
                } elseif (!is_numeric($k)) {
                    $this->values[strtolower($k)] = $v;
                }
            }
        }
    }

    /**
     * Abstract method to be implemented.
     */
    public abstract function toString();
    
    /**
     * Returns the property content by its name.
     * 
     * If property did not exists then the execution will be silence without
     * any warning or error report.
     * 
     * @param string $name property name
     * @return mixed|false content of the specifique property | FALSE not exist
     */
    public function __get($name) {
        $index = strtolower((string) $name);
        if (isset($this->values[$index])) {
            return $this->values[$index];
        }
        return false;
    }
    
    /**
     * Determine with PHP ISSET function if property exists
     * 
     * Aldo, this is also garanted by the <i>__get</i> magic method that will
     * return <i>FALSE</i> if property does not exists.
     * 
     * @param string $name property name
     * @return boolean TRUE exists FALSE does not exists
     */
    public function __isset($name) {
        return isset($this->values[(string) $name]);
    }

    /**
     * Sets the property with its value indentified by its name
     * 
     * @param string $name property name
     * @param mixed|null $value content for the property | NULL to delete it
     */
    public function __set($name, $value) {
        $index = strtolower((string) $name);
        if ($value === null && isset($this->values[$index])) {
            unset($this->values[$index]);
            return;
        }
        $this->values[$index] = $value;
    }

    /**
     * Number of itens in the array of data
     * @return int
     */
    public function count() {
        return count($this->values);
    }

    public function exists($name) {
        return isset($this->values[$name]);
    }

    /**
     * Merge an array of key and its values to the content.
     * @param array $array data to be added
     */
    public function merge($array) {
        if (is_array($array)) {
            $this->values = array_merge($this->values, $array);
        }
        return $this;
    }

    /**
     * How it will react when it is treated like a string.
     * 
     * Will return the implementation present in the abstract method on the
     * class that will extends this abstraction.
     * 
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }

    /**
     * Redefine the name of the tag and maintain the content.
     * 
     * @param string $tag old tag name
     * @param string $newtag new tag name
     */
    public function renewTag($tag, $newtag) {

        $t = strtolower($tag);
        $nt = strtolower($newtag);
        if ($t !== $nt && !empty($t) && !empty($nt)) {
            foreach ($this->values as $k => $v) {
                if ($k === $t) {
                    $this->values[$nt] = $v;
                    unset($this->values[$k]);
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * The array of data 
     * @return array
     */
    public function toArray() {
        return $this->values;
    }

}
