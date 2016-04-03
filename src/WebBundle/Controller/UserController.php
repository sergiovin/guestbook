<?php

namespace WebBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AppController
{
    public function loginAction()
    {
        // The security layer will intercept this request
        return new JsonResponse(
            array(
                'code' => 200
            )
        );
    }

    public function loginCheckAction(Request $request)
    {
        // The security layer will intercept this request
    }

    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}
