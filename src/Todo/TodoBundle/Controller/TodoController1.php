<?php

namespace Todo\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Todo\TodoBundle\Entity\Todo;
use FOS\RestBundle\Controller\FOSRestController;

class TodoController extends FOSRestController
{
    public function indexAction()
    {
        $em    = $this->getDoctrine()->getManager();
        $todos = $em->getRepository('TodoBundle:Todo')->findAll();

        foreach ($todos as $todo) {
            $todoArray[] = array(
                'id'     => $todo->getId(),
                'todo'   => $todo->getTodo(),
                'active' => $todo->getActive()
            );
        }
        $response = new JsonResponse($todoArray);
        return $response;
    }

    // public function addAction()
    // {
    //     $em = $this->getDoctrine->getManager();
    //     $todos = new Todo();
    //     $todos->setTodo($todo);

    // }

    public function getTodoAction($id)
    {
        return $this->container->get('doctrine.entity_manager')->getRepository('Page')->find($id);
    }


}
