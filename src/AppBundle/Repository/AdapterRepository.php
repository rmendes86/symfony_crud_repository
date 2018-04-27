<?php

namespace AppBundle\Repository;

use DateTime;

/**
 * Class AdapterRepository
 * @package AppBundle\Repository
 */
class AdapterRepository extends \Doctrine\ORM\EntityRepository implements AdapterRepositoryInterface
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
        if (empty($id)) {
            $entityName = $this->getEntityName();
            $entity = new $entityName();
            $entity->setCreated(new DateTime());
        } else {
            $entity = $this->find($id);
            $entity->setUpdated(new DateTime());
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

    /**
     * Remove Entity
     *
     * @param $id
     *
     * @return bool|\Exception
     */
    public function remove($id)
    {
        $entity = $this->find($id);

        if (empty($entity)) {
            return new \Exception('entity_not_found');
        }

        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            return $e;
        }

        return true;
    }

    /**
     * Hydrate Data
     *
     * @TODO Check hydrator class on symfony
     *
     * @param array $data
     * @param $object
     *
     * @return mixed
     */
    public function hydrateData(array $data, $object)
    {
        foreach ($data as $key => $value) {
            $methodCall = 'set' . ucfirst($key);
            if (method_exists($object, $methodCall)) {
                $object->$methodCall($value);
            }
        }

        return $object;
    }
}
