<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SendMailService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /////////////////////////Ici j'ai suivie le tutto benois gambier ////////////////////////////////////////////////////////

    //je crée une route pour recevoir un lien au renseignement de mon mail.
    #[Route(path: '/lostpass', name: 'app_lostpass')]
    public function lostpass(
        Request $request, 
        UserRepository $userRepository, 
        TokenGeneratorInterface $tokenGeneratorInterface, 
        EntityManagerInterface $entityManagerInterface,
        SendMailService $mail 
        ): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);

        $form->handleRequest($request);

        //ici dans le DD je vois le mail que j'envoie dans le reset template.
        //dd($form);

        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisateur par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            //ici je retrouve l'email de l'utilisateur en BDD.
            //dd($user);
            
            //On verifie si on a un utilisateur.
            if($user){

            //ici je génère un token de réinitialisation
            $token = $tokenGeneratorInterface->generateToken();
            //ici je vois le token de mon user
            //dd($token);

            $user->setResetToken($token);
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            //On génère un lien de réinitialisation du mot de passe
            $url = $this->generateUrl('app_resetpass', ['token' => $token], urlGeneratorInterface::ABSOLUTE_URL);

            //On crée les données de l'email.
            $context = compact('url', 'user');

            //Envoie du mail.
            $mail->send(
                'no-reply@e-commerce.fr',
                $user->getEmail(),
                'Réinitialisation de mot de passe',
                'password_reset',
                $context
            );

                $this->addFlash('sucess', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }
            //ici je dit a l'utilisateur qu'il ne trouve pas le mail.
            $this->addFlash('danger', 'Un problème est survenu');
            
            //je redirige sur login.
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/lostpass.html.twig', [
            'ResetPasswordFormType' => $form->createView()
        ]);
    }

    //je crée une route pour recevoir un lien au renseignement de mon mail.
    //j'appel la route dans le $url plus haut dans le code.
    #[Route(path: '/resetpass/{token}', name: 'app_resetpass')]
    public function resetPass(
        string $token,
        Request $request,
        UsersRepository $userRepository,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $passwordHasher
        ): Response
    {
        //On verifie si ce token est dans la base de donnée.
        $user = $userRepository->findOneByResetToken($token);

        //Si j'ai un user alors va dans le formulaire du resetpassword.
        if($user){
            //Ici j'appel la class de mon formulaire.
            $form = $this->createForm(PasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                //On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();

                //j'affiche une alerte message.
                $this->addFlash('success', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/resetpass.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        //Si j'ai pas de user envoie une alerte.
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}
