<?php
/**
 * Our own implementation of Symfony\Component\Validator\Constraints\RegexValidator, to catch empty and null values in ParamFetcher
 *
 */

namespace VT\ApiBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use VT\ApiBundle\Validator\VTRegex;

/**
 * Validates whether a value match or not given regexp pattern
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Joseph Bielawski <stloyd@gmail.com>
 *
 * @api
 */
class VTRegexValidator extends ConstraintValidator
{
    /**
     * @Annotation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof VTRegex) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Regex');
        }

        // This is the part that gave problems
        //
        //if (null === $value || '' === $value) {
        //    return;
        //}


        if (!(null === $value || '' === $value) && !is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string)$value;

        if ($constraint->match xor preg_match($constraint->pattern, $value)) {
            $this->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}