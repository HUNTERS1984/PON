<?php

namespace CoreBundle\Exception;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Yaml;

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

    public function __construct(ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function validate($data, $constraints = null, $groups = null)
    {
        $errors = $this->validator->validate($data, $constraints, $groups);
        if ($errors->count() > 0) {
            return [
                'code' => (int)$this->translator->trans($errors[0]->getMessage(), [], 'codes'),
                'message' => $this->translator->trans($errors[0]->getMessage()),
                'data' => []
            ];
        }

        return false;
    }

    public function throwError($message, $description = "")
    {
        return [
            'code' => (int)$this->translator->trans($message, [], 'codes'),
            'message' => !empty($description)? $description: $this->translator->trans($message),
            'data' => []
        ];
    }
}