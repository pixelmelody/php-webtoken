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

use pixelmelody\phpwebtoken\formats\JSONFormat;
use pixelmelody\phpwebtoken\formats\contracts\FormatInterface;

/**
 * PartAbstract
 *
 * Abstract object to implement on classe that will represent one of the parts 
 * of a <i>Token</i>, like header, payload and signature. It includes 
 * <i>claims</i> management and cache.
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
abstract class PartAbstract {

    /**
     * Instance of object that implements FormatInterface
     * @var FormatInterface 
     */
    private $format;

    /**
     * Constructor
     * 
     * @param string $content data for management in the expected format
     * @param string $format format type to use in the data content
     */
    function __construct($content, $format = null) {

        $fn = strtoupper($format);
        $cls = dirname(__NAMESPACE__) . "\\formats\\{$fn}Format";
        if (class_exists($cls)) {
            $this->format = new $cls($content);
        } else {
            $this->format = new JSONFormat($content);
        }
    }

    /**
     * Merge an array of key and its values to the content.
     * @param array $array data to be added
     */
    public function merge($array) {
        $this->format->merge($array);
    }

    /**
     * Return the content of a claim.
     * 
     * @param string $name claim name
     * @return string|false content or FALSE if claim name does not exist
     */
    public function getClaim($name) {
        return $this->format->$name;
    }

    /**
     * Return the element type format implemented. 
     * @return string
     */
    public function getFormatType() {
        return $this->format->getType();
    }

    /**
     * Set a claim content by its name.
     * 
     * @param string $name claim name
     * @param string $value claim content and value
     * @param boolean $update TRUE forces always an update FALSE
     * @return self
     */
    public function setClaim($name, $value, $update = true) {
        if ($update !== true && isset($this->format->$name)) {
            return $this;
        }
        $this->format->$name = $value;
        return $this;
    }

    /**
     * Return the a PHP array of data managed by the object at the time.
     * @return array data
     */
    public function toArray() {
        return $this->format->toArray();
    }

    /**
     * How it will react when it is treated like a string.
     * @return string
     */
    public function __toString() {
        return (string) $this->format;
    }

    /**
     * Redefine the name of the tag and maintain the content.
     * 
     * @param string $tag old tag name
     * @param string $newtag new tag name
     */
    protected function renewTag($tag, $newtag) {
        $this->format->renewTag($tag, $newtag);
    }

}
