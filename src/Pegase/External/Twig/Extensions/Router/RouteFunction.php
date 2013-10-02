<?php

namespace Pegase\External\Twig\Extensions\Router;
use \Pegase\External\Twig\Extensions\FunctionInterface;

class RouteFunction implements FunctionInterface {

  private $sm;

  public function __construct($sm) {
    $this->sm = $sm;
  }

  public function get_name() {
    return 'route';
  }

  public function fn($route_name, $params = array()) {
    $path_s = $this->sm->get('pegase.core.router');
    
    return $path_s->generate($route_name, $params);
  }

}

