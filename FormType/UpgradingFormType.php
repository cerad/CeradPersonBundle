<?php
namespace Cerad\Bundle\PersonBundle\FormType;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class UpgradingFormType extends AbstractType
{   
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_upgrading'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          
            'label'    => 'Working on Upgrade',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            
            'empty_value' => 'Working on Upgrade',
            'empty_data'  => null
            
        ));
    }    
    protected $choices = array
    (
        'No'       => 'No',
        'Yes'      => 'Yes',
      //'SeeNotes' => 'See Notes',
   );    
}

?>
