<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\RestBundle\View\View;
use Exception;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/api/user")
     */
    public function getAction()
    {
        //@TODO Apply service
        $data = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/api/user")
     *
     * @param Request $request
     *
     * @return View
     */
    public function createAction(Request $request)
    {
        //@TODO Apply service
        $data = $request->request->all();
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(['name' => new Assert\NotBlank()]);
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return $this->view($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entity = $this->getDoctrine()->getRepository('AppBundle:User')->save($data);

        // @TODO apply transformers to response api
        return $this->view(
            $data,
            $entity instanceof Exception ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_OK
        );
    }

    /**
     * @Rest\Delete("/api/user/{id}")
     *
     * @param integer $id
     *
     * @return View
     */
    public function removeAction($id)
    {
        //@TODO Apply service
        $result = $this->getDoctrine()->getRepository('AppBundle:User')->remove($id);

        return $result instanceof Exception
            ? $this->view(['error_on_delete'], Response::HTTP_INTERNAL_SERVER_ERROR)
            : $this->view(['ok'], Response::HTTP_OK);
    }
}
