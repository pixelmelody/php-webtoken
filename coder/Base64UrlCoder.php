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

namespace pixelmelody\phpwebtoken\coder;

use pixelmelody\phpwebtoken\contracts\CoderInterface;

/**
 * Base64UrlCoder
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class Base64UrlCoder implements CoderInterface {

    /**
     * Construtor
     */
    function __construct() {
        
    }

    public function decode($data) {
        $tmp = (string) $data;
        if(empty($tmp)){
            return false;
        }
        $tmp .= str_repeat('=', 4 - (strlen($tmp) % 4));
        return base64_decode(strtr($tmp, '-_', '+/'));
    }

    /**
     * Return a string encoded base on the Base64 format.
     * 
     * Can return de value of FALSE if $data is empty as in the PHP Empty. Any
     * characters on the Base64 will be removed for URL safety, like '='.
     * 
     * @param string $data
     * @return mixed boolean encoded string FALSE if impossible to encode data
     */
    public function encode($data) {
        $tmp = base64_encode((string) $data);
        if (empty($tmp)) {
            return false;
        }
        return str_replace('=', '', strtr($tmp, '+/', '-_'));
    }

}
