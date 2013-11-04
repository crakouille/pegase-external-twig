<?php

namespace Pegase\External\Twig\Services;

use \Pegase\Core\Service\Service\ServiceInterface;
use \Pegase\External\Twig\Extensions\Path\PathFunction;
use \Pegase\External\Twig\Extensions\Router\RouteFunction;
use \Pegase\External\Twig\Extensions\Controller\RenderFunction;

use \Pegase\External\Twig\Loader\ExtensionLoader;

use Pegase\Core\Exception\Objects\PegaseException;

class TwigService implements ServiceInterface {

  private $sm;
  private $loader;
  private $twig;

  public function __construct($sm, $params = array()) {

    // initialization
    $this->sm = $sm;
    
    $root = $sm->get('pegase.core.path')->get_root();

    $loader = new \Twig_Loader_Filesystem(
      array(
        $root,
        "/"
      )
    );

    $this->twig = new \Twig_Environment(
      $loader,
      array('debug' => true, 'strict_variables' => true)
      /*, 
      array('cache' => $root . 'app/cache/twig')*/
    );

    $this->twig->addExtension(new \Twig_Extension_Debug());

    $this->loader = new ExtensionLoader($sm);
    $this->loader->set_service($this);

    $this->loader->load_from_yml("app/config/twig.yml");
  }

  public function get_twig() {
    return $this->twig;
  }

  public function render($file, $params = array()) {
  
    // Quand on envoie une exception dans le template twig,
    // twig la catch ! Il faut catcher l'exception twig ...
    // et renvoyer l'exception
    try {
      $ret = $this->twig->render($file, $params);
    }
    catch(\Twig_Error $e) {
      if($e->getPrevious() == null) // si c'est une exception de twig
        throw $e;
      else
        throw new PegaseException($e->getPrevious());
    }

    return $ret;
  }
}

