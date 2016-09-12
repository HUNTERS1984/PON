<?php

namespace CoreBundle\Exception;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExceptionHandler
{
    /**
     * @var ValidatorInterface
    */
    protected $validator;

    /**
     * @var TranslatorInterface
    */
    protected $translator;

    public function __construct(ValidatorInterface $validator,TranslatorInterface $translator)
    {
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function validate($data, $exceptionClass)
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new $exceptionClass($this->translator->trans($errors[0]->getMessage()));
        }
    }

    public function throwError($message, $exceptionClass)
    {
        throw new $exceptionClass($this->translator->trans($message));
    }
}