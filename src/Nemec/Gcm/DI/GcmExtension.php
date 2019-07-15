<?php

namespace Nemec\Gcm\DI;

use Nette;

class GcmExtension extends Nette\DI\CompilerExtension {
    
    public $defaults = array(
        'apiKey' => 0,
    );
    
    private $configuredProviders = array();
    
    public function loadConfiguration() {
        $builder = $this->getContainerBuilder();
        $config = $this->getConfig();
        
        if (isset($config['apiKey'])) {
            $config = array('default' => $config);
        }
        
        if (empty($config)) {
            throw new \Exception('Please configure the GCM extensions.');
        }
        
        foreach ($config as $name => $conf) {
            if (!is_array($conf) || empty($conf['apiKey'])) {
                throw new \Exception('Please configure the GCM extensions.');
            }
            
            $conf = Nette\DI\Config\Helpers::merge($conf, $this->defaults);
            
            $this->processProvider($name, $conf);
        }
        
        $builder->addDefinition($this->prefix('registry'))->setClass('Nemec\Gcm\Registry', array(
            $this->configuredProviders,
            $builder->parameters[$this->name]['defaultProvider'],
        ));
    }
    
    public function processProvider($name, array $config) {
        $builder = $this->getContainerBuilder();
        
        if (!isset($builder->parameters[$this->name]['defaultProvider'])) {
            $builder->parameters[$this->name]['defaultProvider'] = $name;
        }
        
        $serviceId = $this->prefix($name . '.provider');
        
        $builder->addDefinition($serviceId)
            ->setClass(
                'Nemec\Gcm\Provider',
                array($config['apiKey'])
            );
        
        $this->configuredProviders[$name] = $serviceId;
        
        return $this->prefix('@' . $name . '.provider');
    }

}