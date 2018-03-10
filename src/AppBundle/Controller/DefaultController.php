<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('Pages/dashboard.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/maps", name="maps")
     */
    public function mapsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('Pages/maps.html.twig');
    }

    /**
     * @Route("/user", name="user")
     */
    public function userAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('Pages/user.html.twig',[
            'user'=>$this->getUser()
        ]);
    }

    /**
     * @Route("/employee", name="employee")
     */
    public function employeeAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('Pages/employee.html.twig', [
            'users' => $users
        ]);
    }


    /**
     * @Route("/reports", name="reports")
     */
    public function reportsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('Pages/reports.html.twig');
    }


}
