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
 * rsaSignerAbstract
 *
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class rsaSignerAbstract extends SignerAbstract {

    /**
     * Construtor
     */
    function __construct($digest_method) {
        parent::__construct($digest_method);
    }

    public function sign($content, $key) {
        $k = openssl_pkey_get_private($key);
        if ($k !== false) {
            $signature = '';
            if (openssl_sign($content, $signature, $k, $this->getDigestMethod())) {
                return $signature;
            }
        }
        return false;
    }

    public function verify($content, $signature, $key) {
        if (!empty($content) && !empty($signature)) {
            $k = openssl_pkey_get_public($key);
            if ($k !== false) {
                return openssl_verify($content, $signature, $k, $this->getDigestMethod()) === 1;
            }
        }
        return false;
    }

    /**
     * How it will react when it is treated like a string.
     * @return string will return the digest algorithm
     */
    public function __toString() {
        return "RSA-{$this->getDigestMethod()}";
    }

}
