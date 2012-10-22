<?php

namespace Suin\CSRFFirewallBundle\EventListener;

use ReflectionMethod;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Annotations\Reader;
use Suin\CSRFFirewallBundle\Helper\FormToken;
use Suin\CSRFFirewallBundle\Helper\FormTokenInjector;
use Suin\CSRFFirewallBundle\Annotations\CSRF;

class FirewallListener
{
    /** @var string */
    private $secret;
    /** @var string */
    private $name;
    /** @var Session */
    private $session;
    /** @var Reader */
    private $reader;
    /** @var FormTokenInjector */
    private $formTokenInjector;

    /**
     * @param string $secret
     * @param string $name
     */
    public function __construct($secret, $name)
    {
        $this->secret  = $secret;
        $this->name    = $name;
    }

    /**
     * Set session
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Set annotation reader
     * @param Reader $reader
     */
    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Set form token injector
     * @param FormTokenInjector $formTokenInjector
     * @return FirewallListener
     */
    public function setFormTokenInjector(FormTokenInjector $formTokenInjector)
    {
        $this->formTokenInjector = $formTokenInjector;
        return $this;
    }

    /**
     * Event for kernel.controller
     * @param FilterControllerEvent $event
     * @throws HttpException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if ( is_callable($event->getController()) === false ) {
            return;
        }

        $controller = $event->getController();
        $method = new ReflectionMethod($controller[0], $controller[1]);

        /** @var $annotation CSRF */
        $annotation = $this->reader->getMethodAnnotation($method, 'Suin\CSRFFirewallBundle\Annotations\CSRF');

        if ( $annotation instanceof CSRF and $annotation->checkIsEnabled() === false ) {
            return;
        }

        /** @var $request Request */
        $request = $event->getRequest();

        if ( $request->isMethod('POST') === false ) {
            return;
        }

        if ( $request->get($this->name) != $this->generateTokenString() ) {
            throw new HttpException(400, 'The CSRF token is invalid.');
        }
    }

    /**
     * Event for kernel.response
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /** @var $response Response */
        $response = $event->getResponse();
        $newContent = $this->formTokenInjector->inject($response->getContent(), $this->generate());
        $response->setContent($newContent);
    }

    /**
     * Generate token string
     */
    private function generateTokenString()
    {
        if ( $this->session->isStarted() === false ) {
            $this->session->start();
        }

        return sha1($this->secret.$this->session->getId());
    }

    /**
     * @return FormToken
     */
    private function generate()
    {
        return (new FormToken)
            ->setName($this->name)
            ->setValue($this->generateTokenString());
    }
}
