<?php
namespace CoreBundle\Utils;

use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\Translation\TranslatorInterface;

class Response
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public static function getData($data = [], $pagination = [])
    {
        $result = [
            "code"      =>  1000,
            "message"   =>  'OK',
            "data"      =>  $data
        ];
        if(!empty($pagination)) {
            $result['pagination'] = $pagination;
        }
       return $result;
    }

    /**
     * @param string $message
     * @return BaseResponse
     */
    public function getSuccessMessage($message = '', $data = null)
    {
        return new BaseResponse(
            json_encode(
                [
                    'status' => true,
                    'message' => $this->translator->trans($message),
                    'data' => $data
                ]
            )
        );
    }

    /**
     * @param string $message
     * @return BaseResponse
     */
    public function getFailureMessage($message = '')
    {
        return new BaseResponse(json_encode(['status' => false, 'message' => $this->translator->trans($message)]));
    }

    /**
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }
}