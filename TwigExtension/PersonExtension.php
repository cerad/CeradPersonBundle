<?php
namespace Cerad\Bundle\PersonBundle\TwigExtension;

use \Twig_Extension;
use \Twig_Filter_Method;

/* ============================================================
 * Only need to format the phone
 * Should probably be in the PhoneBundle?
 */
use Cerad\Bundle\PersonBundle\DataTransformer\PhoneTransformer;

class PersonExtension extends Twig_Extension
{
    public function getName()
    {
        return 'cerad_person__twig_extension';
    }
    public function getFilters()
    {
        return array(            
            'cerad_phone' => new Twig_Filter_Method($this, 'phone'),   
        );
    }
    public function getFunctions()
    {
        return array();
    }
    protected $phoneTransformer;
    
    public function phone($value)
    {
        if (!$this->phoneTransformer) $this->phoneTransformer = new PhoneTransformer();
        
        return $this->phoneTransformer->transform($value);
    }
}
?>
