<?php

namespace services\dns;

class Factory
{
  public function getImpl($site)
  {
    return new LoopiaImpl();
  }
}
