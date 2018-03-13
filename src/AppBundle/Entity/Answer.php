<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03/13/2018
 * Time: 6:18 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="answer")
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Job")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;


    /**
     * @ORM\Column(type="text")
     */
    private $response;


    /**
     * @ORM\Column(type="date")
     */
    private $answered_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getAnsweredAt()
    {
        return $this->answered_at;
    }

    /**
     * @param mixed $answered_at
     */
    public function setAnsweredAt($answered_at)
    {
        $this->answered_at = $answered_at;
    }



}