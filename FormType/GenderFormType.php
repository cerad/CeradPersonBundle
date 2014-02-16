<?php
namespace Cerad\Bundle\PersonBundle\FormType;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class GenderFormType extends AbstractType
{   
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_gender'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          
            'label'    => 'Gender',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
            
          //'empty_value' => 'Gender',
          //'empty_data'  => null
            
        ));
    }    
    protected $choices = array
    (
        'M' => 'Male',
        'F' => 'Female',
   );    
}

?>
