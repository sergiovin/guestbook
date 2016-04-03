<?php

namespace WebBundle\Listener;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ConvertToHtmlResponse
{
    public function onKernelResponse(FilterResponseEvent $event)
    {

        if (!$event->isMasterRequest()) {
            return;
        }

        return;

        $request = $event->getRequest();

        // Only send back HTML if the requestor allows it
        if (!$request->headers->has('Accept') || (false === strpos($request->headers->get('Accept'), 'text/html'))) {
            return;
        }

        if ($request->headers->has('X-Requested-With') && (false !== strpos($request->headers->get('X-Requested-With'), 'XMLHttpRequest'))) {
            return;
        }

        if ($request->headers->has('Content-Type') && (false !== strpos($request->headers->get('Content-Type'), 'multipart/form-data'))) {
            return;
        }

        $response = $event->getResponse();

        if ($response instanceof BinaryFileResponse) {
            return;
        }

        $prettyprint_lang = null;
        switch ($request->getRequestFormat()) {
            case 'json':
                $prettyprint_lang = 'js';
                $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;

            case 'xml':
                $prettyprint_lang = 'xml';
                $content = $response->getContent();
                break;
            default:
                $content = $response->getContent();
                break;
        }

        if ($prettyprint_lang) {
            $content =
                '<html><body>' .
                    '<pre class="prettyprint lang-' . $prettyprint_lang . '">'  .
                        htmlspecialchars($content).
                    '</pre>' .
                    '<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>' .
                '</body></html>';
        }

        $response->setContent($content);

        // Set the request type to HTML
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        $request->setRequestFormat('html');

        // Overwrite the original response
        $event->setResponse($response);
    }
}
