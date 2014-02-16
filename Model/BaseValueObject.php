<?php
namespace Cerad\Bundle\PersonBundle\Model;

/* ====================================================================
 * See if majic can be used to limit access to only declared properties
 * 
 * These are muttable objects suitable for use as dto's for form processing
 */
class BaseValueObject
{
    public $propNames;
    
    public function hydrate($config)
    {
        // Should be cleaner
        if (!is_array($config) && !($config instanceOf \ArrayAccess)) return false;
       
        foreach($this->propNames as $propName)
        {
            $this->$propName = isset($config[$propName]) ? $config[$propName] : null;
        }
        return true;
    }
}

?>
