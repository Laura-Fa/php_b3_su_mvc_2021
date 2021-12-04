# Sciences-U - B3 IW - PHP7-8 MVC from scratch

##  Mécanisme d'authentification basique
Nous avons mis en place un système basique permettant l'inscription et la connexion.

### L'entité User 
Nous avons repris l'entité User et ses attributs id, username, password et email. Nous avons décidé que le username devait être unique dans la base et que le password devait contenir au minimum 8 caractères. Ces conditions sont donc à vérifiées lors de l'inscription. 

### Inscription

#### La vue

#### Le controller
Nous avons créé un nouveau controller AuthController qui est en charge d'ajouter un nouvel utilisateur dans la base et de l'authentifier.
Dans un premier temps, la méthode register vérifie que les entrées utilisateurs sont correctes : username non vide et unique et password non vide et de 8 caractères au minimum. En cas de non respect de ces critères, nous levons une exception RegisterException. C'est au niveau d'index.php que nous gérons l'erreur en affichant un message d'erreur à l'utilisateur.
Dans un second temps, nous ajoutons l'utilisateur dans la base. Le mot de passe est chiffré avec la password_hash et l'algorithme de hachage Argon2id plus sécurisé que BCRYPT.

#### Authentification




