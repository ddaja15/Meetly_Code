<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/show/users")
     */
    public function showUsersAction()
    {

        $em = $this->getDoctrine()->getManager();
        $users = $em ->getRepository('AppBundle:User')->findAll();
        return $this->render('Test/test.html.twig', [
            'users'=>$users
        ]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/show/user/{name}")
     */
    public function showUserAction($name)
    {
        $em = $this->getDoctrine()->getManager();

        $sql="select * from user where name='$name'";

        $stmt = $em->getConnection()->prepare($sql);

        $stmt->execute();

        $users = $stmt->fetchAll();

//        $users = $em ->getRepository('AppBundle:User')->findAll();

        return $this->render('Test/test.html.twig', [
            'user'=>$users[0]
        ]);
    }


}
