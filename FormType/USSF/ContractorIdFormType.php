<?php
namespace Cerad\Bundle\PersonBundle\FormType\USSF;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Cerad\Bundle\PersonBundle\DataTransformer\USSF\ContractorIdTransformer     as IdTransformer;
use Cerad\Bundle\PersonBundle\ValidatorConstraint\USSF\ContractorIdConstraint  as IdConstraint;

class ContractorIdFormType extends AbstractType
{
    protected $fake;
    
    public function getParent() { return 'text'; }
    public function getName()   
    { 
        return $this->fake ? 'cerad_person_ussfc_id_fake' : 'cerad_person_ussfc_id'; 
    }
    public function __construct($fake = false)
    {
        $this->fake = $fake;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Do the double transformer to handle errors
        $transformer = new IdTransformer($this->fake);
        $builder->addModelTransformer($transformer);
        $builder->addViewTransformer ($transformer);
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'       => 'USSF ID (16-digits)',
            'constraints' => new IdConstraint(),
            'attr'  => array(
                'placeholder' => 'USSF ID (16-digits)',
                'size' => 20),
        ));
    }
}

?>
