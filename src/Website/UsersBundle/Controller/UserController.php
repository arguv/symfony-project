<?php

namespace Website\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Website\UsersBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserController extends Controller
{
    /**
     * Get all users entity.
     *
     */
    public function indexAction()
    {
        try {
            $roleUsers = [];
            $userManager = $this->container->get('fos_user.user_manager');
            $users =  $userManager->findUsers(array('roles'=>'ROLE_USER'));

            foreach ($users as $user) {
                if (!in_array("ROLE_ADMIN", $user->getRoles())) {
                    array_push($roleUsers, $user);
                }
            }
            return $this->render('WebsiteUsersBundle:Users:show.html.twig', array(
                'users' => $roleUsers,
            ));
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
            return new RedirectResponse($this->generateUrl('users_index'));
        }
    }

    /**
     * Edit user entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('Website\UsersBundle\Form\UserFormType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->validationForm($data['fos_user_user']);
            try {
                $user->setUsername($data['fos_user_user']['username']);
                $user->setEmail($data['fos_user_user']['email']);
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Successfuly updated');
                return new RedirectResponse($this->generateUrl('users_index'));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
                return new RedirectResponse($this->generateUrl('users_index'));
            }
        }

        return $this->render('WebsiteUsersBundle:Users:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * Validation Form.
     *
     */
    private function validationForm($data)
    {
        $validator = $this->get('validator');
        $input = ['username' => $data["username"], 'email' => $data["email"]];
        $constraints = new Assert\Collection([
            'username' => [new Assert\Type('string')],
            'email' => [new Assert\Email()],
        ]);

        $violations = $validator->validate($input, $constraints);

        if (count($violations) > 0) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $errorMessages = [];
            foreach ($violations as $violation) {
                $accessor->setValue($errorMessages,
                    $violation->getPropertyPath(),
                    $violation->getMessage());
            }

            $this->get('session')->getFlashBag()->add('error', $errorMessages);
            return new RedirectResponse($this->generateUrl('users_edit'));

        } else {
            return true;
        }
    }

}
