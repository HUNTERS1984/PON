<?php

namespace CoreBundle\Exception;

use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Translation\DataCollectorTranslator;

class ExceptionHandler
{
    /**
     * @var RecursiveValidator
    */
    protected $validator;

    /**
     * @var DataCollectorTranslator
    */
    protected $translator;

    public function __construct(RecursiveValidator $validator,DataCollectorTranslator $translator)
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