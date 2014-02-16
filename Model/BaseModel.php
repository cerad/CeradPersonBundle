<?php
namespace Cerad\Bundle\PersonBundle\Model;

/* ==================================================
 * Was hping to avoid this but no easy way to inject this sort of stuff
 * Maybe a trait sometime
 * 
 * Besides, might be nice to be able to listen for this stuff at the model level
 */
use Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;

class BaseModel implements NotifyPropertyChanged
{
    /* ========================================================================
     * Property change stuff
     */
    protected $listeners = array();

    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->listeners[] = $listener;
    }    
    protected function onPropertyChanged($propName, $oldValue = null, $newValue = null)
    {
        foreach ($this->listeners as $listener) 
        {
            $listener->propertyChanged($this, $propName, $oldValue, $newValue);
        }
    }
    /* ===============================================
     * TODO: Verify value objects work properly
     * VO want to clone
     * If the relations are the same then just ignore
     */
    protected function onPropertySet($name,$newValue)
    {
        $oldValue = $this->$name;
        
        // Same object instance or scaler value
        if ($oldValue === $newValue) return;
        
        /* ===================================
         * Different instance but same values
         * Cloned for VO
         * Need to setup some tests
         */
        if ($oldValue ==  $newValue) return;
        
        // Value changed
        $this->$name = $newValue;
        
        $this->onPropertyChanged($name,$oldValue,$newValue);    
    }
    /* ========================================================
     * Simple guid format
     */
    protected function genGuid() 
    { 
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', 
            mt_rand(0,     65535), mt_rand(0,     65535), mt_rand(0, 65535), 
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), 
            mt_rand(0,     65535), mt_rand(0,     65535));  
    }
    /* ==========================================
     * Not really sure if this shoud go here or not
     * It's basically for database versioning
     * But it might be useful after commits and stuff
     */
    protected $version;
}
?>
