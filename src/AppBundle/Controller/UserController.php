<?php
/**
 * Created by PhpStorm.
 * User: nwe
 * Date: 06.07.19
 * Time: 18:21
 */

namespace AppBundle\Controller;

use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user**/
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Welcome '. $user->getEmail());

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),//查看 services.yml
                    'main'//查看 security.yml 中firewall中的键名
                );
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
