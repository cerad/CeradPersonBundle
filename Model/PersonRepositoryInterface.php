<?php

namespace Cerad\Bundle\PersonBundle\Model;

use Cerad\Bundle\PersonBundle\Model\Person as PersonModel;

interface PersonRepositoryInterface
{
    public function find($id);
    public function findAll();
    
    public function findByGuid  ($guid);
    public function findByFedKey($fedKey);
    
    public function clear();
    public function commit();
    
    public function save  ($item);
    public function delete($item);
}
?>
