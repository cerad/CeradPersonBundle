<?php
namespace Cerad\Bundle\PersonBundle\FormType;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Basic list of states
 */
class StateFormType extends AbstractType
{
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_state'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'    => 'Home State',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
        ));
    }
    protected $choices = array
    (
        'AL' => 'Alabama',
        'AR' => 'Arkansas',
        'GA' => 'Gerogia',
        'LA' => 'Louisiana',
        'MS' => 'Mississippi',
        'TN' => 'Tennessee',
        'ZZ' => 'See Notes',
    );    
}

?>
