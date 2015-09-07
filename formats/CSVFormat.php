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

namespace pixelmelody\phpwebtoken\formats;

/**
 * Formato CSV - Comma Separated Values.
 * 
 * TODO Work in progess
 * 
 * Parser de conteúdo de um token em formato <i>CSV</>. Alguns são os casos em
 * que este formato pode ser utilizado, sendo que este permite eliminar a 
 * utilização de formatos mais elaborados que contemplam vários níveis de 
 * conteúdo e delimitadores mais elaborados.
 * 
 * Não se trata de um formato CSV puro pois tem em conta que os campos são 
 * separados por 'virgulas' mas no entanto estes contemplam o nome do campo e
 * o seu conteúdo separados por um sinal de igual. ex: campo1=test,campo2=test2
 * 
 * Se a implementação do token não requer um conteúdo muito elaborado então este
 * será o formato que permite reduções de tamanho.
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class CSVFormat extends \pixelmelody\phpwebtoken\contracts\FormatAbstract {

    /**
     * Construtor
     * 
     * @param type $content
     */
    function __construct($content) {

        $buffer = [];
        if (is_string($content) || is_object($content)) {
            $buffer = explode(',', (string) $content);
        } elseif (is_array($content)) {
            $buffer = $content;
        }

        parent::__construct($buffer);
    }

    public function getType() {
        return "CWT";
    }

    /**
     * Retorna o conteúdo codificado em formato CSV
     * @return string 
     */
    public function toString() {

        $buffer = "";
        if ($this->count() > 0) {
            foreach ($this->toArray() as $k => $v) {
                $buffer .= "{$k}={$v},";
            }
        }
        return strlen($buffer) > 0 ? substr($buffer, 0, -1) : "";
    }

}
