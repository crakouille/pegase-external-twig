<?php

namespace Pegase\External\Twig\Extensions\Controller;
use \Pegase\External\Twig\Extensions\FunctionInterface;

class RenderFunction implements FunctionInterface {

  private $sm;

  public function __construct($sm) {
    $this->sm = $sm;
  }

  public function get_name() {
    return 'render';
  }

  public function fn($controller, $method, $params = array()) {
    $router = $this->sm->get('pegase.core.router');

    $c = $router->instancy_controller($controller);
    $response = call_user_func_array(array($c, $method), $params);
    
    echo $response->get_content();

    return null;
  }

}

