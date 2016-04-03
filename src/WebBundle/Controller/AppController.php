<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebBundle\Adapter\Command;
use WebBundle\Adapter\Result;

class AppController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'WebBundle::index.html.twig'
        );
    }

    public function processAction(Request $request)
    {
        $result = '';

        if ($request->attributes->get('_commandClass')) {
            $command = Command::create($request);
            $result = $this->get('command_handler')->execute($command)->get();
        }

        $jmsgroup = $request->attributes->get('_jmsgroup');
        if ($request->query->get('_jmsgroup')) {
            $jmsgroup = $request->query->get('_jmsgroup');
        }


        if (!$request->attributes->get('template')) {
            $result = $this->get('serialization')->serialize($result, 'json', 'web.' . $jmsgroup);
        }

        return $this->render(
            $request->attributes->get('template', 'WebBundle::response.json.twig'),
            array(
                'code' => 200,
                'result' => $result
            ),
            new Response()
        );
    }
}
