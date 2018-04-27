<?php

namespace AppBundle\Repository;

/**
 * Interface AdapterRepositoryInterface
 * @package AppBundle\Repository
 */
interface AdapterRepositoryInterface
{
    /**
     * Save User into database
     *
     * @param array $data
     * @param null $id
     *
     * @return mixed
     */
    public function save(array $data, $id = null);

    /**
     * Remove Entity
     *
     * @param $id
     *
     * @return bool|\Exception
     */
    public function remove($id);
}
