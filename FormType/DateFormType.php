<?php
namespace Cerad\Bundle\PersonBundle\FormType;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * For inputting a date, assuming copy/paste
 */
class DateFormType extends AbstractType
{   
    public function getParent() { return 'birthday'; }
    public function getName()   { return 'cerad_person_date'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          
            'label'    => 'DOB (mm/dd/yyyy)',
            'required' => false,
            'input'    => 'datetime',
            'widget'   => 'single_text',
            'format'   => 'M/d/yyyy',
        ));
    }
}

?>
