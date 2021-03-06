<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use DateTime;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends AdapterRepository
{
    /**
     * Save User into database
     *
     * @param array $data
     * @param null $id
     *
     * @return mixed
     */
    public function save(array $data, $id = null)
    {
        /** @var User $entity */
        if (empty($id)) {
            $entityName = $this->getEntityName();
            $entity = new $entityName();
            $entity->setCreated(new DateTime());
        } else {
            $entity = $this->find($id);
            $entity->setUpdated(new DateTime());
        }

        if (! empty($data['group'])) {
            $entity->setGroup($this->getEntityManager()->getReference('AppBundle\Entity\Groups',$data['group']));
            unset($data['group']);
        }

        $entity = $this->hydrateData($data, $entity);

        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            return $e;
        }

        return $entity;
    }
}
