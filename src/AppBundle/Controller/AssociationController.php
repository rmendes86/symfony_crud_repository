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
 * Class AssociationController
 * @package AppBundle\Controller
 */
class AssociationController extends FOSRestController
{
    /**
     * @Rest\Put("/api/association/group/user/{id}")
     *
     * @param $id
     * @param Request $request
     *
     * @return View
     */
    public function updateAction(Request $request, $id)
    {
        //@TODO Apply service and create validator to group id
        $data = $request->request->all();
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(['group' => new Assert\NotBlank()]);
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return $this->view($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entity = $this->getDoctrine()->getRepository('AppBundle:User')->save($data, $id);

        // @TODO apply transformers to response api
        return $this->view(
            $data,
            $entity instanceof Exception ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_OK
        );
    }

    /**
     * @Rest\Delete("/api/association/group/user/{id}")
     *
     * @param $id
     *
     * @return View
     */
    public function removeAction($id)
    {
        //@TODO Apply service
        $result = $this->getDoctrine()->getRepository('AppBundle:User')->save(['group' => null], $id);

        return $result instanceof Exception
            ? $this->view(['error_on_remove_association'], Response::HTTP_INTERNAL_SERVER_ERROR)
            : $this->view(['ok'], Response::HTTP_OK);
    }
}
