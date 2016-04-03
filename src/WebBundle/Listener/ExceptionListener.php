<?php

namespace WebBundle\Listener;

use Application\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Templating\EngineInterface;
use WebBundle\Adapter\Serializer;

class ExceptionListener
{
    private $serializer;
    private $templateEngine;

    public function __construct(Serializer $serializer, EngineInterface $templateEngine)
    {
        $this->serializer = $serializer;
        $this->templateEngine = $templateEngine;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {        
        $exception = $event->getException();

        $response = new Response();

        // HttpExceptionInterface is a special type of exception that holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof BadRequestException && count($exception->getErrors())) {
            $result = array(
                'msg' => $exception->getMessage(),
                'errors' => $exception->getErrors()->toArray()
            );
        } else {
            $result = $exception->getMessage();
        }

        $response->setContent(
            $this->templateEngine->render(
                'WebBundle::response.json.twig',
                array(
                    'code' => $response->getStatusCode(),
                    'result' => $this->serializer->serialize($result, 'json')
                )
            )
        );

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
