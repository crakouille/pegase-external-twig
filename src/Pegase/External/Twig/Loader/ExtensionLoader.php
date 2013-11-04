<?php

namespace Pegase\External\Twig\Loader;

use Pegase\Core\Service\Service\ServiceInterface;
use Pegase\Core\Exception\Objects\PegaseException;

class ExtensionLoader {
  
  private $sm;
  private $_ts; // twig service

  public function __construct($sm) {
    $this->sm = $sm;
  }

  public function set_service($ts) {
    $this->_ts = $ts;
  }

  public function load_from_yml($yml_file, $module = null) {
    $yaml = $this->sm->get('pegase.component.yaml.spyc');
    $module_manager = $this->sm->get('pegase.core.module_manager');

    if($module != null) {
      $yml_file1 = $yml_file;
      $yml_file = $module_manager->get_file($module, $yml_file);

      if($yml_file == null)
        throw new PegaseException("File " . $yml_file1 . " doesn't exists in module " . $module );
    }
    else
      ; 

    if($yml_file == null)
      return;

    $extensions = $yaml->parse($yml_file);

    foreach($extensions as $e_name => $s) {
      if(is_array($s)) {

        if(key_exists('import', $s)) {
          $this->load_from_yml($s['import']['file'], $s['import']['module']);
          continue;
        }
      
        if(!key_exists('type', $s)) {
          throw new PegaseException($e_name . " should contain the 'type' field.");
        }

        if(!key_exists('class', $s)) {
          throw new PegaseException($e_name . " should contain the 'class' field.");
        }

        if($s["type"] == "function") {

          if(!key_exists('method', $s)) {
            throw new PegaseException($e_name . " should contain the 'method' field.");
          }

          $class = new $s["class"]($this->sm);
          $function = new \Twig_SimpleFunction($e_name, array($class, $s["method"]));
          $this->_ts->get_twig()->addFunction($function);
        }
        
        /*else if($s["type"] == "filter") {
          if(!key_exists('method', $s)) {
            throw new PegaseException($e_name . " should contain the 'method' field.");
          }

          $class = new $s["class"]($this->sm);
          $function = new \Twig_SimpleFunction($class->get_name(), array($class, $s["method"]));
          $this->_ts->addFunction($function);
        }*/
      }
      else
        throw new PegaseException($e_name . "should be an Array."); 
    }
    // end foreach
  }
}

