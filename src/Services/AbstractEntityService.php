<?php

namespace App\Services;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;


abstract class AbstractEntityService
{
    protected ObjectManager $em;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->em = $this->doctrine->getManager();
        $this->init();
    }

    protected function init()
    {

    }

    protected function save(object $object = null): void
    {
        if (!is_null($object)) {
            $this->em->persist($object);
        }
        $this->em->flush();
    }

    protected function delete(object $object = null): void
    {
        if (!is_null($object)) {
            $this->em->remove($object);
        }
        $this->em->flush();
    }


}