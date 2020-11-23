<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class EmailExists
 * @package App\Validator
 * @Annotation
 */
class EmailExists extends Constraint
{
    public string $message = "Oups! Cette adresse email n'existe pas encore dans notre bdd./!\\";
}
