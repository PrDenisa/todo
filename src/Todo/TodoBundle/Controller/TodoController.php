<?php

namespace Todo\TodoBundle\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Todo\TodoBundle\Entity\Todo;
use Todo\TodoBundle\Form\TodoType;
use Doctrine\DBAL\DBALException;

class TodoController extends Controller
{

    public function indexAction()
    {
        try {
            $em    = $this->getDoctrine()->getManager();
            $todos = $em->getRepository('TodoBundle:Todo')->findAll();
        } catch (DBALException $e) {
            return new JsonResponse(['status' => false, 'message' => 'Db error, pls try again later!...']);
        }

        foreach ($todos as $todo) {
            $todoArray[] = [
                'id'     => $todo->getId(),
                'todo'   => $todo->getTodo(),
                'active' => $todo->getActive()
            ];
        }

        return new JsonResponse($todoArray);
    }

    public function getTodoAction($id)
    {
        try {
            $todo = $this->getDoctrine()->getManager()->getRepository('TodoBundle:Todo')->find($id);
        } catch (DBALException $e) {
            return new JsonResponse(['status' => false, 'message' => 'Db error, pls try again later!...' . $e]);
        }

        if (!$todo) {
            return new JsonResponse(['status' => false, 'message' => 'not existent todo' ]);
        }

        $response = [
            'id'     => $todo->getId(),
            'todo'   => $todo->getTodo(),
            'active' => $todo->getActive()
        ];
        return new JsonResponse($response);
    }

    public function createAction(Request $request)
    {
        $param = $request->request->all();

        if (!isset($param['todo'])) {
            return new JsonResponse(['status' => false, 'message' => 'Missing parameter todo!']);
        }

        if (empty($param['todo']) || !is_string($param['todo'])) {
            return new JsonResponse(['status' => false, 'message' => 'Invalid parameter todo!']);
        }

        $todo = new Todo();
        $todo->setTodo($param['todo']);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
        } catch(DBALException $e) {
            return new JsonResponse(['status' => false, 'message' => 'Db error, pls try again later!...']);
        }

        return new JsonResponse(['status' => true, 'message' => 'Saved']);
    }

    public function deleteAction(Request $request, $id)
    {
        try {
            $em     = $this->getDoctrine()->getManager();
            $result = $em->getRepository('TodoBundle:Todo')->deleteTodo($id);
        } catch (Exception $e) {
            return new JsonResponse(['status' => false, 'message' => $e->getMessage()]);
        }

        if (0 === $result) {
            return new JsonResponse(['status' => false, 'message' => 'Not found!']);
        }

        return new JsonResponse(['status' => true, 'message' => 'Todo with ' . $id . ' was deleted!']);
    }

    public function updateAction(Request $request, $id)
    {
        $param = $request->request->all();

        if (!isset($param['todo']) && !isset($param['active'])) {
            return new JsonResponse(['status' => false, 'message' => 'Missing parameters!']);
        }

        if (empty($param['todo']) || empty($param['active']) || !is_string($param['todo']) || !is_numeric($param['active'])) {
            return new JsonResponse(['status' => false, 'message' => 'Invalid parameters!']);
        }

        try {
            $em     = $this->getDoctrine()->getManager();
            $result = $em->getRepository('TodoBundle:Todo')->updateTodo($id, $param);
        } catch (Exception $e) {
            return new JsonResponse(['status' => false, 'message' => $e->getMessage()]);
        }

        if (0 === $result) {
            return new JsonResponse(['status' => false, 'message' => 'Not found!']);
        }

        return new JsonResponse(['status' => true, 'message' => 'Todo with id: ' . $id . ' was updated!']);
    }
}
