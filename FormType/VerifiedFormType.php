<?php
namespace Cerad\Bundle\PersonBundle\FormType;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class VerifiedFormType extends AbstractType
{   
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_verified'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          
            'label'    => 'Verified',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            
            'empty_value' => 'Verified',
            'empty_data'  => null
            
        ));
    }    
    protected $choices = array
    (
        'Yes' => 'Yes',
        'No'  => 'No',
        'IP'  => 'IP',
   );    
}

?>
