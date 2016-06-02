<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

abstract class BaseEntityManager
{
    protected $em;
    protected $repo;
    protected $logger;

    public function __construct(
        EntityManager               $em,
        EventDispatcherInterface    $dispatcher,
        LoggerInterface             $logger,
                                    $repoPath
    ) {
        $this->em           = $em;
        $this->repo         = $this->em->getRepository($repoPath);
        $this->dispatcher   = $dispatcher;

        // todo: alert logger that manager has been created
    }

    abstract public function createNew ();
}