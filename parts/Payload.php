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

namespace pixelmelody\phpwebtoken\parts;

use pixelmelody\phpwebtoken\contracts\PartAbstract;

/**
 * Payload
 * 
 * In computing, payload (sometimes referred to as a data body) 
 * refers to the load of a data transmission.
 * 
 * It is the part of the transmitted data, which is the fundamental purpose of
 * transmission, excluding the information sent to it (such as headers or 
 * metadata, also known as complementary data, which may contain, among other 
 * information, to identify the source and destination data).
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class Payload extends PartAbstract {

    /**
     * Construtor
     */
    function __construct($content = "", $format = null) {
        parent::__construct($content, $format);
    }
    
    public function __toString() {
        $value= parent::__toString();
        return empty($value) ? "{}" : $value;
    }
}
