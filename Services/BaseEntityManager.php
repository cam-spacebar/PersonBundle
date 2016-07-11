<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

abstract class BaseEntityManager
{
    protected $em;
    protected $dispatcher;
    protected $logger;
    protected $repo;

    protected $class;

    /**
     * BaseEntityManager constructor.
     * @param EntityManager             $em
     * @param                           $class
     * @param EventDispatcherInterface  $dispatcher
     * @param LoggerInterface           $logger
     */
    public function __construct($em, $class, $dispatcher, $logger) {
        $this->em           = $em;
        $this->repo         = $this->em->getRepository($class);
        $this->dispatcher   = $dispatcher;
        $this->logger       = $logger;


        $metadata       = $this->em->getClassMetadata($class);
        $this->class    = $metadata->getName();

        // todo: alert logger that manager has been created
    }

    abstract public function createNew ();
}