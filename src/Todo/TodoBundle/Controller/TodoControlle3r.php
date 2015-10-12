<?php

namespace Todo\TodoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Todo\TodoBundle\Entity\Todo;
use Todo\TodoBundle\Form\TodoType;

/**
 * Todo controller.
 *
 */
class TodoController extends Controller
{

    /**
     * Lists all Todo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TodoBundle:Todo')->findAll();

        return $this->render('TodoBundle:Todo:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Todo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Todo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('todo_show', array('id' => $entity->getId())));
        }

        return $this->render('TodoBundle:Todo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Todo entity.
     *
     * @param Todo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Todo $entity)
    {
        $form = $this->createForm(new TodoType(), $entity, array(
            'action' => $this->generateUrl('todo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Todo entity.
     *
     */
    public function newAction()
    {
        $entity = new Todo();
        $form   = $this->createCreateForm($entity);

        return $this->render('TodoBundle:Todo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Todo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TodoBundle:Todo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Todo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TodoBundle:Todo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Todo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TodoBundle:Todo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Todo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TodoBundle:Todo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Todo entity.
    *
    * @param Todo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Todo $entity)
    {
        $form = $this->createForm(new TodoType(), $entity, array(
            'action' => $this->generateUrl('todo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Todo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TodoBundle:Todo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Todo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('todo_edit', array('id' => $id)));
        }

        return $this->render('TodoBundle:Todo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Todo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TodoBundle:Todo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Todo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('todo'));
    }

    /**
     * Creates a form to delete a Todo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
