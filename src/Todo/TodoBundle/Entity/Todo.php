<?php

namespace Todo\TodoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Todo
 *
 * @ORM\Table(name="todos_paul")
 * @ORM\Entity(repositoryClass="Todo\TodoBundle\Entity\TodoRepository")
 */
class Todo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="todo", type="string", length=255)
     */
    private $todo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set todo
     *
     * @param string $todo
     *
     * @return Todo
     */
    public function setTodo($todo)
    {
        $this->todo = $todo;

        return $this;
    }

    /**
     * Get todo
     *
     * @return string
     */
    public function getTodo()
    {
        return $this->todo;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Todo
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}

