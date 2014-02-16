<?php
namespace Cerad\Bundle\PersonBundle\ValidatorConstraint\USSF;

use Symfony\Component\Validator\Constraint;

class ContractorIdConstraint extends Constraint
{
    public $message = 'Must be 16-digits';
    public $pattern = '/^(USSFC)?\d{16}$/';
    public $match   = true;
    
    public function validatedBy()
    {
        return 'Symfony\Component\Validator\Constraints\RegexValidator';
    }
}

?>
