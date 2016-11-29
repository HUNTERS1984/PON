<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Serializator\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /**
     * List all Category
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $result = $this->getManager()->listStoreFromAdmin($params);

        return $this->render(
            'AdminBundle:Store:index.html.twig',
            [
                'stores' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }

    public function searchAction(Request $request)
    {
        /** @var AppUser $user */
        $user = $this->getUser();
        $params = $request->query->all();
        $params['query'] = isset($params['q']) ? $params['q'] : '';
        if($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->listStoreFromAdmin($params);
        } else{
            $result = $this->getManager()->listStoreFromClient($params, $user);
        }

        $response = new JsonResponse();
        $data = $this->getSerializer()->serialize($result, ['list']);
        return $response->setData($data);
    }

    /**
     * @return StoreManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
