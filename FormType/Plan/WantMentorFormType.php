<?php
namespace Cerad\Bundle\PersonBundle\FormType\Plan;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class WantMentorFormType extends AbstractType
{   
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_plan_want_mentor'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          
            'label'    => 'Want Mentor',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
            
            'empty_value' => 'Want Mentor?',
            'empty_data'  => null
            
        ));
    }    
    protected $choices = array
    (
        'no'    => 'No',
        'yes'   => 'Yes',
   );    
}

?>
