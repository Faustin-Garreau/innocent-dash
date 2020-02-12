<?php 
    namespace App\Controllers; 
    use App\Models\AppManager;
    use App\Validator;

    class AppController extends Controllers {
            private $manager;
            function __construct()
            {
                $this->manager = new AppManager(); 
                parent::__construct();
            }

            public function create()
            {
                require VIEW.'candide.php';
            }

            public function store() {
                $this->validator->validate([
                    'name' => ['required', 'alpha'],
                    'firstname' => ['required', 'alpha'],
                    'link' => ['required', 'alphaNumDash']
                ]);
        
                if ($this->validator->errors()) {
                    $_SESSION["errors"] = $this->validator->errors();
                    $this->redirect('/homedash');
                }
        
                $name = $this->manager->find($_POST["name"]);
                $firstname = $this->manager->find($_POST["firstname"]);
        
                
                isset($name) ? $_SESSION["errors"]["name"] == "Ce nom est déja utilisé" : NULL;
                isset($firstname) ? $_SESSION["errors"]["firstname"] == "Ce prénom est déja utilisé" : NULL;
                if ($_SESSION["errors"]) {
                    $this->redirect('/homedash');
                }
                
                $this->manager->store();
                $this->redirect('/dashboard/'. $_POST["firstname"]);
            }

            public function show($firstname)
            {
                $candidature = $this->manager->find($firstname);
                require VIEW.'show.php';
            }
        
            public function showProfil($user) {
                $profil = $this->manager->find($user);
                require VIEW.'profile.php';
            }
        
            public function profil($firstname) {
                $this->validator->validate([
                    'firstname' => ['required', 'alpha'],
                    'email' => ['required', 'email'],
                    'discord' => ['required', 'alphaNumDash'],
                    'birthday' => ['required', 'alphaNum'],
                ]);

                if ($this->validator->errors()) {
                    $_SESSION["errors"] = $this->validator->errors();
                    $this->redirect('/dashboard/'. $firstname.'/profile');
                }

                $firstname = $this->manager->find($_POST["firstname"]);

                if ($_SESSION["errors"]) {
                    $this->redirect('/dashboard/'. $firstname.'/profile');
                }
                
                $this->manager->store();
                $this->redirect('/dashboard/'. $_POST["firstname"]);
            }

        public function homeDash() {
            require VIEW.'homedash.php';
        }


        }