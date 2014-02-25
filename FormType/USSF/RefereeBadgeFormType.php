<?php
namespace Cerad\Bundle\PersonBundle\FormType\USSF;

use Symfony\Component\Form\AbstractType;
//  Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ==================================================================
 * Use this to collect and partially validate a region number
 * The transformer will yield AYSORxxxx
 */
class RefereeBadgeFormType extends AbstractType
{ 
    public function getParent() { return 'choice'; }
    public function getName()   { return 'cerad_person_ussfc_referee_badge'; }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'    => 'USSF Referee Badge',
            'choices'  => $this->refereeBadgeChoices,
            'multiple' => false,
            'expanded' => false,
            
            'empty_value' => 'USSF Badge',
            'empty_data'  => null
        ));
    }    
    protected $refereeBadgeChoices = array
    (
        'None'     => 'None',
        'Grade_9'  => 'Grade 9',
        'Grade_8'  => 'Grade 8',
        'Grade_7'  => 'Grade 7',
        'Grade_6'  => 'Grade 6',
        'Grade_5'  => 'Grade 5',
        'Grade_4'  => 'Grade 4',
        'SeeNotes' => 'See Notes',
   );    
}

?>
