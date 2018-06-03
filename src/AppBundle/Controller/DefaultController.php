<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Faker\Provider\DateTime;
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

        $em = $this->getDoctrine()->getManager();

        // Employee Nr
        $sql = "SELECT COUNT(id) as employee_nr FROM user WHERE is_admin='0'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $employee_nr = $stmt->fetchAll();

        // Open Jobs
        $sql = "SELECT COUNT(id) as open_jobs FROM job WHERE is_answered = '0'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $open_jobs = $stmt->fetchAll();

        // Due Soon
        $sql = "SELECT COUNT(id) as due_soon FROM job WHERE deadline BETWEEN CURRENT_DATE() AND (CURRENT_DATE() + INTERVAL 7 DAY)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $due_soon = $stmt->fetchAll();

        // Closed Recently
        $sql = "SELECT COUNT(id) as recently FROM answer WHERE answered_at BETWEEN (CURRENT_DATE() - INTERVAL 7 DAY) AND CURRENT_DATE()";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $recently = $stmt->fetchAll();

        // Last Week's opened and closed jobs
        $last_week_opened_jobs = array();
        $last_week_closed_jobs = array();

        for ($i = 6; $i >= 0; $i--) {
            $sql = "SELECT COUNT(id) as opened_jobs FROM job WHERE created_at = (CURRENT_DATE() - INTERVAL $i DAY)";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $opened_jobs = $stmt->fetchAll();
            array_push($last_week_opened_jobs, $opened_jobs[0]['opened_jobs']);


            $sql = "SELECT COUNT(id) as closed_jobs FROM answer WHERE answered_at = (CURRENT_DATE() - INTERVAL $i DAY)";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $closed_jobs = $stmt->fetchAll();
            array_push($last_week_closed_jobs, $closed_jobs[0]['closed_jobs']);
        }


        // Finished before deadline
        $sql = "SELECT COUNT(job.id) as finishedBeforeDeadline FROM job,answer WHERE job.id=answer.job_id and answer.response is not null and job.deadline>=answer.answered_at";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $finishedBeforeDeadline = $stmt->fetchAll();

        // Finished after deadline
        $sql = "SELECT COUNT(job.id) as finishedAfterDeadline FROM job,answer WHERE job.id=answer.job_id and answer.response is not null and job.deadline<=answer.answered_at";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $finishedAfterDeadline = $stmt->fetchAll();

        // Not finished at all
        $sql = "SELECT COUNT(id) as notFinished FROM job WHERE job.is_answered = 0";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $notFinished = $stmt->fetchAll();


        // Second Chart Query

        $sql = "SELECT count(job.id) as closed_jobs, job.user_id as robi, user.name, user.surname, answered_at, (SELECT count(job.id)
                                                                                                                 FROM job
                                                                                                                 WHERE job.user_id = robi
                                        
                                                                                                                 ) as assigned_jobs
                FROM job, user, answer
                WHERE job.user_id = user.id
                AND answer.job_id = job.id
                AND job.is_answered = 1
                AND answer.answered_at BETWEEN (CURRENT_DATE() - INTERVAL 7 DAY) AND CURRENT_DATE()
                GROUP BY user_id
                ORDER BY count(job.id)
                DESC LIMIT 5;";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $top_employees = $stmt->fetchAll();


        // Fourth chart data, Roles and role_count

        $sql = "SELECT count(user.id) as role_count, role
                FROM user
                GROUP BY role
                ORDER BY count(user.id)
                DESC LIMIT 5";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $top_roles = $stmt->fetchAll();

        $colors = array('#68B3C8', '#F3BB45', '#EB5E28', '#7AC29A', '#7A9E9F', 'rgba(104, 179, 200, 0.8)', 'rgba(122, 194, 154, 0.8)');

        $top_roles_colors = array();

        $cnt = 0;
        foreach ($top_roles as $role) {
            $role['color'] = $colors[$cnt++];
            array_push($top_roles_colors, $role);
        }


        // replace this example code with whatever you need
        return $this->render('Pages/dashboard.html.twig', [
            'user' => $this->getUser(),
            'stats' => [
                'employee_nr' => $employee_nr[0]['employee_nr'],
                'open_jobs' => $open_jobs[0]['open_jobs'],
                'due_soon' => $due_soon[0]['due_soon'],
                'recently' => $recently[0]['recently'],
                'last_week_opened_jobs' => $last_week_opened_jobs,
                'last_week_closed_jobs' => $last_week_closed_jobs,
                'finishedBeforeDeadline' => $finishedBeforeDeadline[0]['finishedBeforeDeadline'],
                'finishedAfterDeadline' => $finishedAfterDeadline[0]['finishedAfterDeadline'],
                'notFinished' => $notFinished[0]['notFinished'],
                'top_employees' => $top_employees,
                'top_roles' => $top_roles,
                'top_roles_colors' => $top_roles_colors
            ]
        ]);
    }

    /**
     * @Route("/paper", name="Paper")
     */
    public function paperAction(Request $request)
    {

        return $this->render('Pages/now-ui.html.twig', [

        ]);
    }


    /**
     * @Route("/maps", name="maps")
     */
    public function mapsAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $sql = "select * from user where is_admin='0'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll();

        $sql2 = "SELECT * FROM job WHERE is_answered = 0";
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
        return $this->render('Pages/user.html.twig', [
            'user' => $this->getUser()
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
            'users' => $users, 'num2' => 1, 'status' => 0
        ]);
    }

    /**
     * @Route("/employee/{slug}", name="employeepage")
     */
    public function employeeNewAction(Request $request, $slug)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('Pages/employee.html.twig', [
            'users' => $users, 'num2' => $slug, 'status' => 0
        ]);
    }

    /**
     * @Route("/add/job",name="addJob")
     */
    public function addJobAction(Request $request)
    {

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
        $job->setIsAnswered(0);

        $employee = $request->request->get('job-employee');
        $data = explode(" ", $employee);
        $emp_name = $data[0];
        $emp_surname = $data[1];

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([
            'name' => $emp_name,
            'surname' => $emp_surname
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


        $notification = new Notification();

        $content = "New Job created by " . $this->getUser()->getName() . " " . $this->getUser()->getSurname() .
            ". The job is assigned to " . $user->getName() . " " . $user->getSurname() .
            ". Deadline: " . $request->request->get('job-deadline');

//        $date = new DateTime();

        $notification->setContent($content);
        $notification->setCreatedAt(new \DateTime());

        $em->persist($notification);
        $em->flush();

        return new Response('U kry');
    }


    /**
     * @Route("/delete/job",name="deleteJob")
     */
    public function deleteJobAction(Request $request)
    {

        $delete_id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $sql = "DELETE FROM job WHERE id = $delete_id";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return new Response('U eleminu');
    }

    /**
     * @Route("/get/employee",name="getEmployee")
     */
    public function getEmployeeAction(Request $request)
    {

        $emp_id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $sql = "select * from user wh ere id = $emp_id";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchAll();

        return new Response($user[0]['name'] . " " . $user[0]['surname']);
    }

    /**
     * @Route("/get/notification",name="getNotification")
     */
    public function getNotificationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT * FROM notification WHERE created_at >= DATE_SUB(NOW(),INTERVAL 5 HOUR)";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $notification = $stmt->fetchAll();

        return new Response(json_encode($notification));
    }


    /**
     * @Route("/jobs",name="jobs")
     */
    public function jobsAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT job.id as job_id, user_id, title, deadline, description, form, lat, lng,
                    deadline, priority, reward, job_diff, job.created_at, job.updated_at, is_answered,
                    a.id as answer_id, a.response, answered_at, u.name, u.surname
                FROM job
                JOIN user u ON job.user_id = u.id
                LEFT JOIN answer a ON job.id = a.job_id ORDER BY job.created_at DESC";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $jobs = $stmt->fetchAll();


        return $this->render('Pages/jobs.html.twig', [
            'jobs' => $jobs,
            'response' => $jobs[0]['response']
        ]);
    }

    /**
     * @Route("/search/jobs",name="searchJobs")
     */
    public function searchJobsAction(Request $request)
    {

        $searching = $request->request->get('job-title');

        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT job.id as job_id, user_id, title, deadline, description, form, lat, lng,
                    deadline, priority, reward, job_diff, job.created_at, job.updated_at, is_answered,
                    a.id as answer_id, a.response, answered_at, u.name, u.surname
                FROM job
                JOIN user u ON job.user_id = u.id
                LEFT JOIN answer a ON job.id = a.job_id
                WHERE job.title LIKE '%$searching%'
                ORDER BY job.created_at DESC";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $jobs = $stmt->fetchAll();

        return new Response(json_encode($jobs));
    }


    /**
     * @Route("/delete/jobs",name="deleteJobs")
     */
    public function deleteJobsAction(Request $request)
    {

        $job_id = $request->request->get('job-id');

        $em = $this->getDoctrine()->getManager();
        $sql = "DELETE FROM job WHERE job.id = $job_id";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $answer_id = $request->request->get('answer_id');

        if ($answer_id != null) {
            $sql = "DELETE FROM answer WHERE answer.id = $answer_id AND answer.job_id = $job_id";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
        }


        return new Response("yes");
    }

    /**
     * @Route("/add",name="addAction")
     */

    public function addAction()
    {
        return $this->render("/Pages/add.html.twig", ['status' => 3]);
    }

    /**
     * @Route("/create",name="cremp")
     */
    public function create_new_emp(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('text-username');
        $email = $request->request->get('text-email');
        $name = $request->request->get("text-name");
        $surname = $request->request->get('text-surname');
        $oldate = $request->request->get('text-birthday');
        $newdate = date('Y-m-d', strtotime($oldate));
        $role = $request->request->get('text-role');
        $salary = $request->request->get('text-salary');
        $password = $request->request->get('text-password');
        $conf_passwor = $request->request->get('text-password-conf');
        $admin = $request->request->get('text-admin');
        $phone = $request->request->get('text-phone');

        if ($password == $conf_passwor) {
            $sql = "insert into user (username,password,name,surname,birthday,email,phone_nr,is_admin,role,salary,created_at,updated_at,last_loggin,fuel_consumption)
            values ('$username','$password','$name','$surname','$newdate','$email','$phone',$admin,'.$role.',$salary,000-00-00,000-00-00,000-00-00,0.0)";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            return $this->render("/Pages/add.html.twig", ['status' => 1]);
        } else {
            return $this->render("/Pages/add.html.twig", ['status' => 0]);
        }

    }

    /**
     * @Route("/edit/{slug}",name="editemp")
     */
    public function edit_employee($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "select * from user where id =" . $slug;
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll();
        return $this->render('/Pages/edit.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/edit/conf",name="config")\
     */
    public function edit_employee_confirmation(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('text-username');
        $email = $request->request->get('text-email');
        $phone = $request->request->get('text-phone');
        $role = $request->request->get('text-role');
        $salary = $request->request->get('text-salary');
        $password = $request->request->get('text-password');
        $pass_conf = $request->request->get('text-password-confirm');
        $id = $request->request->get('text-id');

        if ($password == $pass_conf) {
            $sql = "update user set user.username = $username, user.email = $email, user.phone_nr = $phone, user.role = $role, user.salary = $salary, user.password = $password 
             where user.id =" . $id;
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            return $this->redirectToRoute('employee');
        } else {
            $sql = "select * from user where id =" . $id;
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll();
            $stmt->close();

            return $this->render('/Pages/edit.html.twig', ['users' => $users, 'status' => 0]);
        }

    }

    /**
     * @Route("/remove/{slug}",name="rem")
     */
    public function removeEmployee($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "delete from user where user.id=" . $slug;
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        return $this->redirectToRoute('employee');

    }

    /**
     * @Route("search",name="search")
     */
    public function searchEmployee(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $text = $request->request->get('text');
        $sql = "SELECT * from user WHERE name LIKE '%" . $text . "%' OR email LIKE '%" . $text . "%' OR surname LIKE '%" . $text . "%'";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchAll();

        return $this->render("/Pages/employee.html.twig", ['users' => $user, 'num2' => 1, 'status' => 1]);
    }

    /**
     * @Route("/reports", name="reports")
     */
    public function reportsAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $sql_user = "select * from user where user.is_admin=0";
        $sql_chief = "select * from user where user.is_admin=1";
        $stmt1 = $em->getConnection()->prepare($sql_user);
        $stmt1->execute();
        $users = $stmt1->fetchAll();
        $stmt2 = $em->getConnection()->prepare($sql_chief);
        $stmt2->execute();
        $chiefs = $stmt2->fetchAll();

        return $this->render('Pages/reports.html.twig', [
            'users' => $users, 'chiefs' => $chiefs
        ]);
    }
}