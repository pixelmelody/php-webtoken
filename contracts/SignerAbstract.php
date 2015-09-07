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
 * SignerAbstract - Base contract mechanism for an token signer
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
abstract class SignerAbstract {

    /**
     * Base Digest Method
     * @var string
     */
    private $dm;

    /**
     * Construtor
     * 
     * This constructor need a parameter string tag like <i>SHA256</i> or other. 
     * More information follow the link below.
     * 
     * @see http://php.net/manual/en/function.openssl-get-md-methods.php
     * @param string $digest_method 
     */
    function __construct($digest_method) {
        $this->dm = $digest_method;
    }

    /**
     * Token JWT Header Claim tag 'alg'.
     * 
     * The tag must be identical to the file and php class name. All uppercase.
     * 
     * @see https://tools.ietf.org/html/draft-ietf-oauth-json-web-token-32
     * @return string implemented algorithm tag
     */
    public function getAlgorithm() {
        return (new \ReflectionClass($this))->getShortName();
    }
    
    /**
     * Digest method tag implemented.
     * 
     * @see http://php.net/manual/en/function.openssl-get-md-methods.php
     * @return string tag like <i>SHA256</i> or other
     */
    public function getDigestMethod() {
        return $this->dm;
    }
    
    /**
     * Sign the content and return the corresponding signature.
     * 
     * @param string $content data to be signed
     * @param string $key string key or pem rsa private key
     */
    public abstract function sign($content, $key);

    /**
     * Validate a content against a particular signature.
     * 
     * @param string $content data signed with the signature
     * @param string $signature for the data content
     * @param string $key string key or pem rsa public key
     */
    public abstract function verify($content, $signature, $key);
    

}
