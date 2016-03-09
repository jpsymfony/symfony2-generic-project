<?php

namespace App\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\UserBundle\Entity\Password\ChangePassword;

/**
 * @Route("/account")
 */
class ChangePasswordController extends Controller
{
    /**
     * @Route("/change-password", name="change_password")
     * @Method("GET|POST")
     */
    public function changePasswordAction(Request $request)
    {
        $data = new ChangePassword($this->getUser());
        $form = $this->createForm('change_password_form', $data);

        if ($this->getChangePasswordFormHandler()->handle($form, $request)) {
            $this->addFlash('success', 'The password has been changed successfully.');
            return $this->redirect($this->generateUrl('user_dashboard'));
        }

        return $this->render('user/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \App\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getChangePasswordFormHandler()
    {
        return $this->container->get('app_user.change_password.handler');
    }
}
