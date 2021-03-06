<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use OrganisationBundle\Entity\Arbitre;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;
use UserBundle\Form\RegistrationAdminType;
use UserBundle\Form\RegistrationArbitreType;
use UserBundle\Form\RegistrationType;

class RegistrationController extends BaseController
{
    /**
     * @Route("/registration", name="user-registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $adminCreateUser = false;
        if(true === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            if ($currentUser->getRoleString() == 'ROLE_ADMIN') {
                $adminCreateUser = true;
            }
        }
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        if( true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(RegistrationAdminType::class, $user);
        }else{
            $form = $this->createForm(RegistrationType::class, $user);
        }
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if(!$adminCreateUser){
                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                }
                $user->setRoleString($form["roleString"]->getData());
                $userManager->updateUser($user);

                if(!$adminCreateUser) {
                    if (null === $response = $event->getResponse()) {
                        $url = $this->generateUrl('fos_user_registration_confirmed');
                        $response = new RedirectResponse($url);
                    }

                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                }else{
                    $url = $this->generateUrl('admin_home');
                    $response = new RedirectResponse($url);
                }
                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
