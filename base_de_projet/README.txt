
                            Symfony 6 🔝



Je crée un nouveau projet symfony mise en place de mon environnement :

-   J'installe symfony : symfony new my_project_directory --version="6.3.*" --webapp


________________________________________________________________________________

                        🪛 La base de donnée



-   mise en place de la base de donnée dans le .env
-   installation de ORM : composer require symfony/orm-pack
-   installation de doctrine : composer require --dev symfony/maker-bundle

Inscription de la base de donnée sur MYSQL : 

-   php bin/console doctrine:database:create

[ La base de donnée à été crée ]

________________________________________________________________________________

                                   🪛 USER


Crée l'entity USER : 

-   php bin/console make:user
-   php bin/console make:migration
-   php bin/console doctrine:migrations:migrate

Crée une connexion login : 

-   symfony console make:auth

Crée l'enregistrement :

-   symfony console make:registration-form

-   je rajoute : use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
-   je modifie le formulaire : ->add('plainPassword', RepeatedType::class,...etc)
-   je rajoute les regexes dans le formulaire directement avec 12 caractères minimum

Crée un controller :

-   symfony console make:controller

    page home crée


________________________________________________________________________________

                             🪛 Configuration


-   AuthLoginControllerAuthenticator je modifie ligne 52 la redirection login.


________________________________________________________________________________

                             🪛 Dashboard Admin

- installation du bundle : composer require easycorp/easyadmin-bundle
- création du controller : php bin/console make:admin:dashboard

- Dans routes.yaml : 
    
    dashboard:
    path: /admin
    controller: App\Controller\Admin\DashboardController::index

- crée la vue twig : {% extends '@EasyAdmin/page/content.html.twig' %}


________________________________________________________________________________
-> ici j'ai suivie le tutto : https://www.youtube.com/watch?v=ZPqcKl2Izt0&t=2647s
/!\ En cas d'erreur aller dans SecurityController et supprimer après le commentaire tutto.
//supprimer les formulaire et les vue twig + .json les dépendance email.
________________________________________________________________________________

                             🪛 Reset Password

- Création d'une route dans le controller security
- Création d'une page twig pour le reset de password

- Mise en place d'un nouveau formulaire : symfony console make:form
- Dans le form modifier le add avec la valeur qu'on souhaite ( exemple: email )
- Dans la vue appeler le formulaire crée : {{ form(ResetPasswordFormType)}}


________________________________________________________________________________

                             🪛 Reset Password par Email

- composer require symfony/mime: On va pouvoir créer un objet Email.
- composer require symfony/mailer: pour l'envoie d'email.
- Je rajoute le templates twig pour l'envoie de l'email.
- rajouter les variables d'environnement dans le .env : 
            -> MESSENGER_TRANSPORT_DSN=doctrine://default
            -> MAILER_DSN=smtp://EMAIL:PASSWORD@gmail





