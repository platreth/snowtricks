<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\TrickType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RequestPasswordType;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils) {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //
        $form = $this->get('form.factory')
                ->createNamedBuilder(null)
                ->add('_username', null, ['label' => 'Email'])
                ->add('_password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, ['label' => 'Mot de passe'])
                ->add('ok', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['label' => 'Ok', 'attr' => ['class' => 'btn-primary btn-block']])
                ->getForm();
        return $this->render('security/login.html.twig', [
                    'mainNavLogin' => true, 'title' => 'Connexion',
                    //
                    'form' => $form->createView(),
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }

    /**
     * @Route("/request/password", name="requestPassword")
     */
    public function requestPassword(Request $request, \Swift_Mailer $mailer, UserRepository $userRepository, EntityManager $em) {
        $user = new User();
        $form = $this->createForm(RequestPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $userExist = $userRepository->findOneBy(array('email' => $user->getEmail()));

            if ($userExist) {
                $user->reloadToken();
                $em->persist($user);
                $em->flush();

                $message = (new \Swift_Message('Réinitialiser votre mot de passe'))
                ->setFrom(array('hugo.platret@gmail.com' => 'hugo' ))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'mail/request_password.html.twig',
                        array(
                            'token' => $user->getToken()
                        )
                    ), 'text/html'
                );
                $mailer->send($message);
            }
            $this->addFlash('success', 'un email de réinitialisation a été envoyé');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/request_password.html.twig', [
            'title' => 'Mot de passe oublié',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset/password/{token}", name="resetPassword")
     */
    public function resetPassword(Request $request, $token) {

        return $token;
    }

}