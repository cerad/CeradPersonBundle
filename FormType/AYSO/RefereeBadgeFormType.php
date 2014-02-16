<?php
namespace Cerad\Bundle\PersonBundle\FormType\AYSO;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class RefereeBadgeFormType extends AbstractType
{   
    public function getName()   { return 'cerad_person_aysov_referee_badge'; }
    public function getParent() { return 'choice'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          //'invalid_message' => 'Unknown Region Number',
            
            'label'    => 'AYSO Referee Badge',
            'choices'  => $this->refereeBadgeChoices,
            'multiple' => false,
            'expanded' => false,
        ));
    }
    
    protected $refereeBadgeChoices = array
    (
        'None'         => 'None',
        'Regional'     => 'Regional',
        'Intermediate' => 'Intermediate',
        'Advanced'     => 'Advanced',
        'National'     => 'National',
        'National_1'   => 'National 1',
        'National_2'   => 'National 2',
        'Assistant'    => 'Assistant',
        'U8Official'   => 'U8',
    );
}

?>
