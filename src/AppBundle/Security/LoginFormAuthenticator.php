<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        $isFormSubmit = $request->attributes->get('_route') === 'security_login' && $request->isMethod('POST');
        //或者
        //$isFormSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
        if (!$isFormSubmit) {
            return; //若非login表单提交，则直接返回，Authenticator不再执行后续方法
        }

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();

        return $data;//若返回任何非空数据，Authenticator会继续执行后续getUser()方法
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];
        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $username]); //若未找到user，则不会后续执行checkCredentials()方法
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];

        if ($password == 'iliketurtles') {
            return true;
        }

        return false;
    }

    protected function getLoginUrl() //若login失败，则返回到下列url
    {
        return $this->router->generate('security_login');
    }

    protected function getDefaultSuccessRedirectUrl() //若login成功，则返回到下列url
    {
        return $this->router->generate('homepage');
    }
}
