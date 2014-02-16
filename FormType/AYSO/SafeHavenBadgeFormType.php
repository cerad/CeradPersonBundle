<?php
namespace Cerad\Bundle\PersonBundle\FormType\AYSO;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class SafeHavenBadgeFormType extends AbstractType
{   
    public function getName()   { return 'cerad_person_aysov_safe_haven_badge'; }
    public function getParent() { return 'choice'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          //'invalid_message' => 'Unknown Region Number',
            
            'label'    => 'AYSO Safe Haven',
            'choices'  => $this->choices,
            'multiple' => false,
            'expanded' => false,
        ));
    }
    
    protected $choices = array
    (
        'None'    => 'None',
        'AYSO'    => 'AYSO',
        'Coach'   => 'Coach',
        'Referee' => 'Referee',
    );
}

?>
