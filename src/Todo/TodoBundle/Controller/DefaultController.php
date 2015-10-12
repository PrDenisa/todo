<?php

namespace Todo\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TodoBundle:Default:index.html.twig', array('name' => $name));
    }
}
