<?php
namespace Cerad\Bundle\PersonBundle\FormType\AYSO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Cerad\Bundle\PersonBundle\DataTransformer\AYSO\RegionIdTransformer as Transformer;

/* ==================================================================
 * The transformer will yield AYSORxxxx
 * Use validation.yml for validation
 */
class RegionIdFormType extends AbstractType
{   
    public function getName()   { return 'cerad_person_ayso_region_id'; }
    public function getParent() { return 'text'; }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new Transformer();
        $builder->addModelTransformer($transformer);
        $builder->addViewTransformer ($transformer);
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'AYSO Region Number (1-1999)',
            'attr'  => array('size' => 4),
        ));
    }
}

?>
