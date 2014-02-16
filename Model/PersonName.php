<?php
namespace Cerad\Bundle\PersonBundle\Model;

/* ==========================================
 * First shot at a value object
 * It's mutable so forms can use it
 * 
 * Seems to work okay, need some doctrine event listeners
 */
class PersonName extends BaseValueObject
{   
    public $full;
    public $first;
    public $last;
    public $nick;
    public $middle;
    
    public function __construct(
        $full   = null, 
        $first  = null, 
        $last   = null, 
        $nick   = null, 
        $middle = null)
    {
        // Suppose could use reflection?
        $this->propNames = array('full','first','last','nick','middle');
        
        // config passed
        if ($this->hydrate($full)) return;
        
        // Just scaler
        foreach($this->propNames as $propName)
        {
            $this->$propName = $$propName;
        }
    }
}
?>
