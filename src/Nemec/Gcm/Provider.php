<?php

namespace Nemec\Gcm;

use ZendService\Google\Gcm\Client;

class Provider extends Client {
    
    public function __construct($apiKey) {
        $this->setApiKey($apiKey);
        
        $client = new \Zend\Http\Client();
        $client->setOptions(array(
            'strictredirects' => true,
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
        ));
        $this->setHttpClient($client);
    }
    
}
