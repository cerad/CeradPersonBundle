<?php

namespace Cerad\Bundle\PersonBundle\Model;

use Cerad\Bundle\PersonBundle\Model\PersonFed;
use Cerad\Bundle\PersonBundle\Model\PersonName;
use Cerad\Bundle\PersonBundle\Model\PersonAddress;

interface PersonInterface
{
  //public function getId();
    
    public function createFed    ($params = null);
    public function createName   ($params = null);
    public function createAddress($params = null);
    
}
?>
