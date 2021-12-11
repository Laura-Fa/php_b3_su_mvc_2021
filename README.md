# Sciences-U - B3 IW - PHP7-8 MVC from scratch

##  Mécanisme d'authentification basique
Nous avons mis en place un système basique permettant l'inscription et la connexion.

### L'entité User 
Nous avons repris l'entité User et ses attributs id, username, password et email. Nous avons décidé que le username devait être unique dans la base et que le password devait contenir au minimum 8 caractères. Ces conditions sont donc à vérifiées lors de l'inscription. 

### Inscription/Authentification

#### Les vues
Nous avons créé deux vues, userRegistrationForm pour afficher le formulaire d'inscription et userLoginForm pour afficher le formulaire de connexion.

#### Le contrôleur
Nous avons créé un nouveau contrôleur AuthController qui gère l'inscription et la connexion.
Les méthodes showRegisterUserForm et showLoginUserForm réalise des redirections vers les 2 vues précédemment citées.

##### Méthode register
Dans un premier temps, la méthode register vérifie que les entrées utilisateurs sont correctes : username non vide et unique et password non vide et de 8 caractères au minimum. En cas de non respect de ces critères, nous levons une exception RegisterException. C'est au niveau d'index.php que nous gérons l'exception en transmettant le message d'erreur à la vue (userRegistrationForm) qui l'affiche.
Dans un second temps, nous ajoutons l'utilisateur dans la base. Le mot de passe est chiffré avec la password_hash et l'algorithme de hachage Argon2id plus sécurisé que BCRYPT.
Suite à son inscription, l'utilisateur est redirigé vers la page de connexion.

##### Méthode login
Cette méthode vérifie que l'utilisateur est présent dans la base et que le mot de passe saisi correspond à celui hashé dans la base de données. De la même façon que pour la méthode register, nous levons une exception LoginException en cas d'erreur, puis le message d'erreur est transmis par index.php à la vue userLoginForm. En cas de réussite, nous enregistrons le pseudo de l'utilisateur dans une variable de session et l'utilisateur est redirigé vers la page de l'application (appPage).

#### Gestion des sessions
Nous utilisons les variables de session. Nous appelons la méthode session_start() qui permet de démarrer une session ou de la reprendre dans index.php avant la méthode execute() du router. Nous savons avant de servir une page si l'utilisateur est connecté ou pas en testant si la variable de session "userName" est présente. La méthode logout du contrôleur AuthController permet à l'utilisateur de se déconnecter en détruisant les variables de session.

#### Gestion des droits
Nous voulons que notre application (la page appPage en l'occurence) ne soit accessible qu'aux membres connectés. Pour cela, avant la méthode execute() du router si l'utilisateur n'est pas connecté et qu'il ne demande pas à créer un compte ou à se connecter, nous affectons à $requestUri le chemin d'accès à la page index. Ainsi, cette page lui est présentée et l'invite à créer un compte ou à se connecter.