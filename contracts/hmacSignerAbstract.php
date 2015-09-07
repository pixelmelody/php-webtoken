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
 * hmacSignerAbstract
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
abstract class hmacSignerAbstract extends SignerAbstract {

    /**
     * Construtor
     */
    function __construct($digest_method) {
        parent::__construct($digest_method);
    }

    public function sign($content, $key) {
        return hash_hmac($this->getDigestMethod(), $content, $key, true);
    }

    public function verify($content, $signature, $key) {
        
        $hash = hash_hmac($this->getDigestMethod(), $content, $key, true);
        if (function_exists('hash_equals')) {
            return hash_equals($signature, $hash);
        }
        $len = min(self::safeStrlen($signature), self::safeStrlen($hash));
        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= (ord($signature[$i]) ^ ord($hash[$i]));
        }
        $status |= (self::safeStrlen($signature) ^ self::safeStrlen($hash));
        return ($status === 0);
    }

    /**
     * How it will react when it is treated like a string.
     * @return string will return the digest algorithm
     */
    public function __toString() {
        return "HMAC-{$this->getDigestMethod()}";
    }

    /**
     * Get the number of bytes in cryptographic strings.
     *
     * @param string
     * @return int
     */
    private static function safeStrlen($str) {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }

}
