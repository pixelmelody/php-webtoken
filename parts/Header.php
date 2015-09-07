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

use pixelmelody\phpwebtoken\signers\HS256;
use pixelmelody\phpwebtoken\contracts\PartAbstract;
use pixelmelody\phpwebtoken\contracts\SignerAbstract;

/**
 * Header
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class Header extends PartAbstract {

    private $claims = ['algorithm' => 'alg', 'type' => "typ"];

    /**
     *
     * @var SignerAbstract;
     */
    private $signer;

    /**
     * Construtor
     * 
     * Implementa um objecto que representa um WebToken Header. De acordo com
     * a norma o cabeçalho contém algumas propriedades como o Algoritmo ou o 
     * tipo de token que este comtempla na sua própria gestão.
     * 
     * Por esta via o conteúdo do cabeçalho pode ser inicializado o que ao 
     * acontecer define o algoritmo e o tipo de token. 
     * 
     * @param string $content bloco que define o cabeçalho do token
     * @param string $format formato para o coder pordefeito JSON
     * @param string $alg etiqueta que define o algoritmo implementado
     */
    function __construct($content = "", $format = null, $alg = 'HS256') {
        
        parent::__construct($content, $format);

        if (!empty($content)) {
            $alg = $this->getClaim($this->claims['algorithm']);
        }
        
        $this->signer = $this->_new_signer_instance($alg);
        $this->setClaim($this->claims['type'], $this->getFormatType());
    }

    /**
     * 
     * @return SignerAbstract
     */
    public function getSigner() {
        return $this->signer;
    }

    public function setAlgorithm($alg) {
        $this->signer = $this->_new_signer_instance($alg);
        return $this->signer !== false;
    }

    public function setAlgorithmTag($newtag) {
        if (is_string($newtag) && !empty($newtag)) {
            $this->renewTag($this->claims['algorithm'], $newtag);
            $this->claims['algorithm'] = $newtag;
        }
    }

    public function setTypeTag($newtag) {
        if (is_string($newtag) && !empty($newtag)) {
            $this->renewTag($this->claims['type'], $newtag);
            $this->claims['type'] = $newtag;
        }
    }

    /**
     * Instanciar e retornar um gestor de assinaturas.
     * 
     * Processo interno para instanciar um gestor de assinaturas que implemente
     * a interface <i>SignerInterface</i> e que esteja presente na pasta 
     * <i>signers</i> do componente.
     * 
     * Para o instanciamento é necessária a indicação da <i>tag</i> que 
     * identifica um algoritmo válido de acordo com a especificação, ver em:
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#page-6
     * 
     * @param string $tag nome da etiqueta que identifica o algoritmo
     * @return SignerInterface|false
     */
    private function _new_signer_instance($tag) {
        $cls = dirname(__NAMESPACE__) . "\\signers\\{$tag}";
        $this->signer = class_exists($cls) ? new $cls() : new HS256();

        if ($this->signer instanceof SignerAbstract) {
            $this->setClaim($this->claims['algorithm'], $this->signer->getAlgorithm());
        }

        return $this->signer;
    }

}
