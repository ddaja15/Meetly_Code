<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $em = $this->getDoctrine()->getManager();
        $sql="select * from user where is_admin='0'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll();

        $sql2 = "SELECT * FROM job WHERE answer IS NULL";
        $stmt2 = $em->getConnection()->prepare($sql2);
        $stmt2->execute();
        $jobs = $stmt2->fetchAll();
        // replace this example code with whatever you need
        return $this->render('Pages/maps.html.twig', [
            'users' => $users,
            'jobs' => $jobs,
        ]);
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


    /**
     * @Route("/add/job",name="addJob")
     */
    public function addJobAction(Request $request){

        $job = new Job();
        $job->setTitle($request->request->get('job-title'));
        $job->setDescription($request->request->get('job-desc'));
        $job->setForm($request->request->get('json-form'));
        $job->setLat($request->request->get('latitude'));
        $job->setLng($request->request->get('longitude'));

        $deadline = date_create($request->request->get('job-deadline'));

        $job->setDeadline($deadline);
        $job->setPriority($request->request->get('job-priority'));
        $job->setReward($request->request->get('job-reward'));
        $job->setJobDiff($request->request->get('job-difficulty'));

        $employee = $request->request->get('job-employee');
        $data = explode(" ",$employee);
        $emp_name = $data[0];
        $emp_surname = $data[1];

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([
            'name'=>$emp_name,
            'surname'=>$emp_surname
        ]);

        $job->setUser($user);

        $job->setUser($user);

        $date = new \DateTime();

        $job->setCreatedAt($date);
        $job->setUpdatedAt($date);

//        Get user name/surname from form and query for the user from the db
//        $user = new User();

        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();

        return new Response('U kry');
    }


    /**
     * @Route("/delete/job",name="deleteJob")
     */
    public function deleteJobAction(Request $request){

        $delete_id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $sql="DELETE FROM job WHERE id = $delete_id";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return new Response('U eleminu');
    }

    /**
     * @Route("/get/employee",name="getEmployee")
     */
    public function getEmployeeAction(Request $request){

        $emp_id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $sql="select * from user where id = $emp_id";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchAll();

        return new Response($user[0]['name']." ".$user[0]['surname']);
    }

}
