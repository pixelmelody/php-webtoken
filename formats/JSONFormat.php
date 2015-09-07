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

use pixelmelody\phpwebtoken\contracts\FormatAbstract;

/**
 * JSON format content manager.
 *
 * @author PixelMelody <lab@pixelmelody.com>
 * @copyright 2015 Pixelmelody PT Portugal
 */
class JSONFormat extends FormatAbstract {

    /**
     * Constructor
     */
    function __construct($content) {

        $buffer = [];
        if (is_string($content) || is_object($content)) {
            $buffer = json_decode((string) $content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $buffer = [];
            }
        } elseif (is_array($content)) {
            $buffer = $content;
        }
        parent::__construct($buffer);
    }

    /**
     * Tag for Token based on the this implementation
     * @return string
     */
    public function getType() {
        return "JWT";
    }

    /**
     * Returns a JSON coded content.
     * 
     * If no content then will provide and ideal and clear for the format.
     * @return string
     */
    public function toString() {
        return $this->count() === 0 ? "" : json_encode($this->toArray());
    }

}
