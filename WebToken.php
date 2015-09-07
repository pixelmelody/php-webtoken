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

namespace pixelmelody\phpwebtoken;

use pixelmelody\phpwebtoken\parts\Header;
use pixelmelody\phpwebtoken\parts\Payload;
use pixelmelody\phpwebtoken\coder\Base64UrlCoder;
use pixelmelody\phpwebtoken\contracts\CoderInterface;

/**
 * Pixelmelody PHP WebToken
 *  
 * Implements a <i>Token</i> for comunications or identity on the web. One set
 *  of information that can be transmitted across multiple platforms.
 * 
 * This is a implementation based on <i>JWT - JSON Web Token</i> and it 
 * concepts, like structure, formats and coders.
 * <code>
 *      {header}.{Content Area / Payload}.{signature}
 * </code> 
 * 
 * By default this object will act as a <i>JWT</i>. The difference may accur for
 * projects that want to implement a different protocol <i>WebToken</i>.
 * 
 * @see http://rfc7159.net/rfc7159
 * @see http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class WebToken {

    /**
     * Defines a CSV Token format
     */
    CONST CSVFormat = 'CSV';

    /**
     * Defines a JSON Token format
     */
    CONST JSONFormat = "JSON";

    /**
     * The header object to manage the token first part.
     * @var Header
     */
    private $header;

    /**
     * The payload object to manage the token second part.
     * @var Payload
     */
    private $payload;

    /**
     * The coder
     * @var CoderInterface 
     */
    private $coder;

    /**
     * The signature text. The token second part.
     * @var string|string content ot FALSE if none
     */
    private $signature;

    /**
     * Constructor
     * 
     * Token object that makes possible to indicate the Token coded string to
     * manage. If no Token is supplied a new one is builded. Also is possible 
     * to indicate a format for data, but if not indicated it assumes the JSON
     * format. Others formats are still a work in progress.
     * 
     * The indication of a coder is optional, when omitted it use <i>Base64</i>.
     * 
     * External coder are also possible, for that matter a class must be creted
     * and implement the interface: pixelmelody\phpwebtoken\coder\CoderInterface
     * <code>
     * class mycoder implements CoderInterface { 
     *      public function decode($data) {
     *          return xxxxx_decode($data);
     *      }
     * 
     *      public function encode($data) {
     *          return xxxxx_encode($data);
     *      }
     * }
     * </code>
     * 
     * @param string $content token content or none to build one 
     * @param string $format null = "JSON"(JWT)
     * @param string $coder null = Base64
     */
    function __construct($content = "", $format = null, $coder = null) {

        $header = false;
        $payload = false;
        $this->signature = false;

        if ($coder instanceof CoderInterface) {
            $this->coder = $coder;
        } else {
            $this->coder = new Base64UrlCoder();
        }

        $e = explode('.', $content);
        if ($e !== false && count($e) === 3) {
            $header = new Header($this->coder->decode($e[0]), $format);
            $payload = new Payload($this->coder->decode($e[1]), $format);
            $this->signature = $this->coder->decode($e[2]);
        }

        $this->header = ($header) ? $header : new Header("", $format);
        $this->payload = ($payload) ? $payload : new Payload("", $format);
    }

    /**
     * Returns the content of a payload specifique claim.
     * 
     * @param string $name claim name
     * @param mixed $default default content if no claim 
     * @return string|false claim content FALSE if not exists
     */
    public function getClaim($name, $default = false) {
        $v = $this->payload->getClaim($name);
        return $default !== false && $v === false ? $default : $v;
    }

    /**
     * The implemented signer algorithm tag name.
     * @return string|false algorithm name FALSE if does not exist
     */
    public function getAlgorithm() {
        $signer = $this->header->getSigner();
        if ($signer !== false) {
            return $signer->getAlgorithm();
        }
        return false;
    }

    /**
     * The implemented digest algorithm tag name.
     * @return string|false algorithm tag FALSE if does not exist
     */
    public function getDigestMethod() {
        $signer = $this->header->getSigner();
        if ($signer !== false) {
            return (string) $signer;
        }
        return false;
    }

    /**
     * The header manager object.
     * @return Header
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * Retorna o conteúdo de uma propriedade do cabeçalho do token
     * 
     * @param string $name nome da propriedade
     * @param mixed $default conteúdo por defeito se não existir propriedade
     * @return string|False conteudo da propriedade FALSE se inexistente
     */
    public function getHeaderClaim($name, $default = false) {
        $v = $this->header->getClaim($name);
        return $default !== false && $v === false ? $default : $v;
    }

    /**
     * The payload manager object.
     * @return Payload
     */
    public function getPayload() {
        return $this->payload;
    }

    /**
     * The token type string tag.
     * 
     * @return string example: "JWT"
     */
    public function getType() {
        /* could not have header */
        return $this->payload->getFormatType();
    }

    /**
     * Defines a new signer algorithm intantiated object by its tag.
     * @param string $tag
     * @return self
     */
    public function setAlgorithm($tag) {
        $this->header->setAlgorithm($tag);
        return $this;
    }

    /**
     * Defines a new signer TAG header to be implemented by the token.
     * @param string $tag
     * @return self
     */
    public function setAlgorithmTag($tag) {
        $this->header->setAlgorithmTag($tag);
        return $this;
    }

    /**
     * Add ou update a payload claim identified by its name.
     * 
     * @param string $name claim name
     * @param string $value claim new content
     * @param bollean $update TRUE update if already exist FALSE not
     * @return self
     */
    public function setClaim($name, $value, $update = true) {
        $this->payload->setClaim($name, $value, $update);
        return $this;
    }

    /**
     * Add ou update a header claim identified by its name.
     * 
     * @param string $name claim name
     * @param string $value claim new content
     * @param bollean $update TRUE update if already exist FALSE not
     * @return self
     */
    public function setHeaderClaim($name, $value, $update = true) {
        $this->header->setClaim($name, $value, $update);
        return $this;
    }

    /**
     * Redefine a new tag for the Token type.
     * @param string $tag
     * @return self
     */
    public function setTypeTag($tag) {
        $this->header->setTypeTag($tag);
        return $this;
    }

    /**
     * Encodes the token with all claims and signed for distribuition.
     * 
     * Some error may occur when build a token:
     * <code>
     *      1.Header is empty without claims
     *      2.Payload is empty (on JWT if empty will return "{}" is not empty)
     * </code>
     * 
     * @return string|boolean coded token FALSE on error
     */
    public function encode($key) {

        $header = $this->coder->encode($this->header);
        if (empty($header)) {
            return false;
        }

        $payload = $this->coder->encode($this->payload);
        if (empty($payload)) {
            return false;
        }
        
        $signer = $this->header->getSigner();

        $content = "{$header}.{$payload}";
        $signature = $signer->sign($content, $key);

        return $signer && $signature !== false ? "{$content}.{$this->coder->encode($signature)}" : false;
    }

    /**
     * Verifies the signature present in the token.
     * 
     * This verification is done with a key supplied with the token in the
     * constructor. If it matches with the produced signature when this method
     * is called, then the token is ok.
     * 
     * IMPORTANT: If a token is been created, then no signature exists, in
     * result, when calling this method it always return FALSE (not valid).
     * 
     * @param string $key chaave para validação da assinatura
     * @param string $signature assinatura para validação
     * @return boolean TRUE assinatura/token válido FALSE não condiz/erro
     */
    public function verify($key, $signature = null) {

        /* no signature, compare impossible */
        if ($this->signature === false) {
            return false;
        }

        /* no header, compare impossible */
        $header = $this->coder->encode($this->header);
        if (!$header) {
            return false;
        }

        /* no payload, compare impossible */
        $payload = $this->coder->encode($this->payload);
        if (!$payload) {
            return false;
        }

        /* no signer no method to execute compare */
        $signer = $this->header->getSigner();
        if ($signer === false) {
            return false;
        }

        return $signer->verify("{$header}.{$payload}", $signature === null ? $this->signature : $signature, $key);
    }

    /**
     * How it will react when it is treated like a string.
     * @return string will return the token not encoded
     */
    public function __toString() {

        $payload = (string) $this->payload;

        if (empty($payload) || $payload === "{}") {
            return (string) $this->header;
        }
        return sprintf('%s.%s', $this->header, $payload);
    }

}
