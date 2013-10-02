<?php

namespace Pegase\External\Twig;

use \Pegase\Core\Module\AbstractModule;
use \Pegase\External\Twig\Services\TwigService;

class TwigModule extends AbstractModule {
  
  public function get_name() {
    return "Template/Twig";
  }
}

