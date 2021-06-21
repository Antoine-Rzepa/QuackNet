QUACKNET
PROJET SYMFONY


Suivre ce process:

Vérifier que vous avez la version php 7.4 ou + :
```bash
symfony check:requirements
``` 

Créer un nouveau projet :
symfony new --full my_project
```bash
symfony new --full QuackNet
``` 

Ce déplacer dans le projet puis :
```bash
composer install
``` 

Configurer la BDD dans le fichier .env :
to use sqlite:
```bash
DATABASE_URL="sqlite:///%kernel.project_dir%/var/app.db"
``` 
puis :
```bash
php bin/console doctrine:database:create
``` 

Créer une classe entité (Quack):
```bash
bin/console make:entity
``` 
Suivre le questionnaire :
 - Nom de l'entité
 - nom du premier paramètre
 - type du paramètre (? pour plus d'infos sur les possibilités)
 - etc ...
 - nom du second paramètre, etc ...

Pour mettre a jour la BDD doctrine :
```bash
bin/console doctrine:schema:update -f
``` 
ou
```bash
bin/console d:s:u -f
``` 

Faire une controller :
```bash
bin/console make:crud
``` 

Pour lancer l'application :
```bash
symfony server:start
``` 

Créer une classe entité (Duck):
```bash
bin/console make:entity
``` 

Créer l'authentificateur (Duck):
```bash
bin/console make:auth
``` 

VOTER

Configurer EasyAdmin

Ajouter EasyAdmin en dépendance :
```bash
symfony composer req "admin:^3"
```
Créer le répertoire
```bash
mkdir src/Controller/Admin/
``` 
Générer un tableau de bord (DashBoard)
```bash
symfony console make:admin:dashboard
``` 
Accepter les réponses par défaut crée par le contrôleur
la nouvelle route /admin est créée
Générer un CRUD pour chaque ENTITY devant être modéré
```bash
symfony console make:admin:crud
``` 

Modifier les CRUD:
```bash
     public function configureMenuItems(): iterable
     {
-        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
-        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
+        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
+        yield MenuItem::linkToCrud('Conferences', 'fas fa-map-marker-alt', Conference::class);
+        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);
     }
```  
puis

```bash   
         public function index(): Response
     {
-        return parent::index();
+        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
+        $url = $routeBuilder->setController(ConferenceCrudController::class)->generateUrl();
+
+        return $this->redirect($url);
     }
``` 