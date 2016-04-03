<?php

namespace InfrastructureBundle\Security;

use Application\Exception\BadRequestException;
use Application\Exception\UnauthorizedException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class UsernamePasswordAuthenticator implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface, AuthenticationEntryPointInterface
{
    private $router;

    /**
    * Constructor
    * @param RouterInterface   $router
    */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $response = new Response(json_encode ( array('code'=>200, 'success'=> true) ));

        if ($request->get('_remember_me') == 1) {
            $response->headers->setCookie(new Cookie('username', $request->get('_username'), time() + (3600 * 48)));
            $response->headers->setCookie(new Cookie('password', $request->get('_password'), time() + (3600 * 48)));
            $response->headers->setCookie(new Cookie('locale', $request->get('_locale'), time() + (3600 * 48), '/', null, false, false));
            $response->headers->setCookie(new Cookie('remember_me', $request->get('_remember_me'), time() + (3600 * 48)));
        } else {
            $response->headers->clearCookie('username');
            $response->headers->clearCookie('password');
            $response->headers->clearCookie('locale');
            $response->headers->clearCookie('remember_me');
        }

        return $response;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new BadRequestException('Login or password invalid');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new UnauthorizedException('FormBased', 'Unauthorized Exception');
    }
}
