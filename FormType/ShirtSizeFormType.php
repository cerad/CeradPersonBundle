<?php
namespace Cerad\Bundle\PersonBundle\FormType;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class ShirtSizeFormType extends AbstractType
{   
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_shirt_size'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          
            'label'    => 'Tee Shirt Size',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            
            'empty_value' => 'Shirt Size??',
            'empty_data'  => null
            
        ));
    }    
    protected $choices = array
    (
        'youths'    => 'Youth Small',
        'youthm'    => 'Youth Medium',
        'youthl'    => 'Youth Large',
        'adults'    => 'Adult Small',
        'adultm'    => 'Adult Medium',
        'adultl'    => 'Adult Large',
        'adultlx'   => 'Adult Large X',
        'adultlxx'  => 'Adult Large XX',
        'adultlxxx' => 'Adult Large XXX',
   );    
}

?>
