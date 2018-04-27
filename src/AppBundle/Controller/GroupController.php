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
 * Class GroupController
 * @package AppBundle\Controller
 */
class GroupController extends FOSRestController
{
    /**
     * @Rest\Get("/api/group")
     */
    public function getAction()
    {
        //@TODO Apply service
        $data = $this->getDoctrine()->getRepository('AppBundle:Groups')->findAll();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/api/group")
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

        $entity = $this->getDoctrine()->getRepository('AppBundle:Groups')->save($data);

        // @TODO apply transformers to response api
        return $this->view(
            $data,
            $entity instanceof \Exception ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_OK
        );
    }

    /**
     * @Rest\Delete("/api/group/{id}")
     *
     * @param integer $id
     *
     * @return View
     */
    public function removeAction($id)
    {
        //@TODO create validator and call
        $entity = $this->getDoctrine()->getRepository('AppBundle:Groups')->find($id);
        if (empty($entity)) {
            return $this->view(['entity_not_found'], Response::HTTP_NOT_FOUND);
        }

        if (! empty($entity->getUsersCollection())) {
            return $this->view(['cannot_remove_entity'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //@TODO Apply service
        $result = $this->getDoctrine()->getRepository('AppBundle:Groups')->remove($id);

        return $result instanceof Exception
            ? $this->view(['error_on_delete'], Response::HTTP_INTERNAL_SERVER_ERROR)
            : $this->view(['ok'], Response::HTTP_OK);
    }
}
