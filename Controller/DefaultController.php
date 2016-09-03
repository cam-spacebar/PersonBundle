<?php

namespace VisageFour\Bundle\PersonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", defaults={"displayProjectPlan" = 1})
     * @Route("/{displayProjectPlan}", name="homepage")
     *
     */
    public function indexAction(Request $request, $displayProjectPlan = false)
    {
        // show project plan on startup
        if (($this->container->get('kernel')->getEnvironment() == 'dev') &&
            ($displayProjectPlan == 1)
        ) {
            return $this->render('Internal/ProjectPlan.html.twig', array());
        }
        return $this->render('PersonBundle:Default:index.html.twig');
    }
}
