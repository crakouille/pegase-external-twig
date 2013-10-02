<?php

namespace Pegase\External\Twig\Extensions;

interface FilterInterface {

  public function get_name();
  public function filter($param);

}

