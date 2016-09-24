<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Post;
use CoreBundle\Form\Type\PostType;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\PostManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PostController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * Create Post
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create post",
     *  requirements={
     *      {
     *          "name"="app_user_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of app user"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\Post",
     *       "groups"={"create_post"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\Post",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @return Response
     */
    public function postAction(Request $request)
    {

        $appUserId = $request->request->get('app_user_id');
        $appUser = null;
        if($appUserId){
            /**@var AppUserManager $appUserManager */
            $appUserManager = $this->getAppUserManager();
            /**@var AppUser $appUser*/
            $appUser = $appUserManager->findOneById($appUserId);

            if(!$appUser) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'app_user.not_found'
                );
            }
        }

        $form = $this->createForm(PostType::class, new Post());
        $form->submit($request->request->all());

        /**@var Post $post*/
        $post = $form->getData();
        $post->setAppUser($appUser);

        if($error = $this->get('pon.exception.exception_handler')->validate($post)) {
            return $error;
        }
        $this->getManager()->createPost($post);
        $post = $this->getSerializer()->serialize($post, ['view','view_post']);

        return $this->view($post, 201);
    }

    /**
     * Update Post
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update post",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of post"
     *      },
     *     {
     *          "name"="app_user_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of app user"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\Post",
     *       "groups"={"create_post"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\Post",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var Post $post*/
        $post = $manager->findOneById($id);
        if(!$post) {
            $this->get('pon.exception.exception_handler')->throwError(
                'post.not_found'
            );
        }

        $appUserId = $request->request->get('app_user_id');
        if($appUserId) {
            /**@var AppUserManager $appUserManager */
            $appUserManager = $this->getAppUserManager();
            /**@var AppUser $appUser*/
            $appUser = $appUserManager->findOneById($appUserId);

            if(!$appUser) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'app_user.not_found'
                );
            }
            $post->setAppUser($appUser);
        }

        $post = $this->get('pon.utils.data')->setData($request->request->all(), $post);

        if($error = $this->get('pon.exception.exception_handler')->validate($post)) {
            return $error;
        }


        $this->getManager()->savePost($post);
        $post = $this->getSerializer()->serialize($post, ['view','view_post']);
        return $this->view($post, 200);
    }

    /**
     * Delete Post
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to delete post",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of post"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Post is not found"
     *   }
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        /**@var PostManager $manager*/
        $manager = $this->getManager();
        /**@var Post $post*/
        $post = $manager->findOneById($id);
        if(!$post) {
            $this->get('pon.exception.exception_handler')->throwError(
                'post.not_found'
            );
        }

        $status = $manager->deletePost($post);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'post.delete_false'
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail Post
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view post",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of post"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\Post",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the post is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Post is not found"
     *   }
     * )
     * @return Response
     */
    public function getAction($id)
    {
        $manager = $this->getManager();
        /**@var Post $post*/
        $post = $manager->findOneById($id);
        if(!$post || !is_null($post->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'post.not_found'
            );
        }

        $data = $this->getSerializer()->serialize($post, ['view','view_post']);

        return $this->view($data, 200);
    }

    /**
     * Get List Post
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list post",
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\Post",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Post is not found"
     *   }
     * )
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $params = $request->query->all();
        $data = $this->getManager()->listPost($params);
        $posts = $this->getSerializer()->serialize($data['data'], ['view','view_post']);
        return $this->view($posts, 200, $data['pagination']);
    }

    /**
     * @return PostManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.post');
    }

    /**
     * @return AppUserManager
     */
    public function getAppUserManager()
    {
        return $this->get('pon.manager.app_user');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
