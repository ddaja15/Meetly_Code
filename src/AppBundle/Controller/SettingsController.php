<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SettingsController extends Controller
{

    /**
     * @Route("/update",name="update")
     */
    public function updateAction(Request $request)
    {
        $un = $request->request->all();

        return $this->render('Test/test.html.twig', [
            'user' => $un
        ]);
    }
}
