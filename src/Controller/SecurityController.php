<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\TrickType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RequestPasswordType;
use App\Form\ResetPasswordType;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
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
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    public function requestPassword(Request $request, Swift_Mailer $mailer, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(RequestPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $userExist = $userRepository->findOneBy(array('email' => $user->getEmail()));

            if ($userExist) {
                $userExist->reloadToken();
                $em->persist($userExist);
                $em->flush();

                $message = (new \Swift_Message('Réinitialiser votre mot de passe'))
                    ->setFrom(array('hugo.platret@gmail.com' => 'hugo'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'mail/request_password.html.twig',
                            array(
                                'token' => $userExist->getToken(),
                                'email' => $userExist->getEmail()
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
     * @Route("/reset/password/{email}/{token}", name="resetPassword")
     * @param Request $request
     * @param $token
     * @param $email
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse
     */
    public function resetPassword(Request $request, $token, $email, UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {

        $user = $userRepository->findOneBy(array('email' => $email));

        $form = $this->createForm(resetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($user->getToken() == $token) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    "Mot de passe modifié avec succès !"
                );
                return $this->redirectToRoute('login');
            }
        } else {
            $this->addFlash(
                'danger',
                "La modification du mot de passe a échoué ! Le lien de validation a expiré !"
            );
        }
        return $this->render('security/reset_password.html.twig', [
            'title' => 'Mot de passe oublié',
            'form' => $form->createView(),
        ]);
    }
}