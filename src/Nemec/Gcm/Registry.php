<?php

namespace Nemec\Gcm;

use Nette;

class Registry {
    
    /**
     * @var array 
     */
    private $providers;
    
    /**
     * @var string 
     */
    private $defaultProvider;
    
    /**
     * @var Nette\DI\Container 
     */
    private $serviceLocator;
    
    public function __construct(
        array $providers,
        $defaultProvider,
        Nette\DI\Container $serviceLocator
    ) {
        $this->providers = $providers;
        $this->defaultProvider = $defaultProvider;
        $this->serviceLocator = $serviceLocator;
    }
    
    /**
     * @param string $name
     * @return Provider
     * @throws \Exception
     */
    public function getProvider($name = null) {
        if ($name === null) {
            $name = $this->defaultProvider;
        }
        
        if (!isset($this->providers[$name])) {
            throw new \Exception("Provider named $name does not exist.");
        }
        
        return $this->getService($this->providers[$name]);
    }
    
    /**
     * @return array
     */
    public function getProviderNames() {
        return $this->providers;
    }
    
    /**
     * @return Provider[]
     */
    public function getProviders() {
        $providers = array();
        foreach ($this->providers as $name => $id) {
            $providers[$name] = $this->getService($id);
        }
        
        return $providers;
    }
    
    /**
     * @return string
     */
    public function getDefaultProviderName() {
        return $this->defaultProvider;
    }
    
    /**
     * @param string $name
     * @throws \Exception
     */
    public function resetProvider($name = null) {
        if ($name === null) {
            $name = $this->defaultProvider;
        }
        
        if (!isset($this->providers[$name])) {
            throw new \Exception("Provider named $name does not exist.");
        }
        
        $this->resetService($this->providers[$name]);
    }
    
    protected function getService($name) {
        return $this->serviceLocator->getService($name);
    }
    
    protected function resetService($name) {
        $this->serviceLocator->removeService($name);
    }
    
}