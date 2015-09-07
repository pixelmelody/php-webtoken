# Pixelmelody PHP WebToken 

This repository contains the easiest token handler for PHP implementations.

## PHP PSR-4 Namespace
```bash
    pixelmelody\phpwebtoken
```

## Installation

With composer to manage your dependencies:

```bash
composer require pixelmelody/phpwebtoken
```


## Some Features

* JWT Token implementation as the [current spec](https://tools.ietf.org/html/rfc7519)
* Possible future formats, others than JSON.
* Easy integration on a PHP Project

## PHP Example handling a token
```php
<?php    
    $token = new WebToken($token_received);
    
    echo "Header Schema Algorithm: ",$token->getAlgorithm();
    echo "Token digest method: ", $token->getDigestMethod();

    echo "Header: ", $token->getHeader();
    echo "Payload: ", $token->getPayload();

    if($token->verify($key)){
        echo 'Token Valid';
    } else {
        echo 'Token not Valid';
    }
?>
```

## PHP Example creating a token
```php
<?php    
    $token = new WebToken(); // <= new token RS256 Algorithm default
    $token->setClaim('test', 'content for the paylod claim test');   

    echo "Header Schema Algorithm: ",$token->getAlgorithm();
    echo "Token digest method: ", $token->getDigestMethod();

    echo "Header: ", $token->getHeader();
    echo "Payload: ", $token->getPayload();

    $tokenvalue = $token->encode($key);
    if($tokenvalue){
        echo "Token: ",$tokenvalue;
    } else {
        echo "Token error on creation";
    }
?>
```

## Important
> **Please always upgrade to latest stable version from master branch and 
beaware that on __dev__ branch changes will ocurr. All issues will **


## Prerequisites

   - PHP 5.4 or above
   - Composer is not required however you will not have a direct implementation.


## Reporting Potential Security Issues

If you have encountered a potential security vulnerability in this library, please
report it at [pixelmelody.com contact form](http://www.pixelmelody.com/#contact). 
We will work with you to verify the vulnerability and patch it.

We request that you contact us via the email address to give the project contributors
a chance to resolve the vulnerability and issue a new release prior to any public
exposure. This helps protect users and provides them with a chance to upgrade and/or
update in order to protect their applications.

The merit on finding it will be added to you and we will mention it on the library repository.

## License
    
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
 
[http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)
  
Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.