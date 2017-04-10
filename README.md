# Site pour un entretien
Basé sur le tuto d'openclassrooms sur symfony
### 

# Installation

## 1. Définir vos paramètres d'application
Vous avez un fichier `app/config/parameters.yml` pour modifier l'access à la data.

## 2. Télécharger les vendors
Utilisez Composer pour cela
`php composer.phar install`

## 3. Créez la base de données
Si la base de données que vous avez renseignée dans l'étape 1 n'existe pas déjà, créez-la :

    php bin/console doctrine:database:create

Puis créez les tables correspondantes au schéma Doctrine :

    php bin/console doctrine:schema:update --dump-sql
    php bin/console doctrine:schema:update --force

Ensuite copiez le fichier `Database/table/category.sql` dans votre base de donnée
    
## 3Bis. Créez la base de données

Si la base de données que vous avez renseignée dans l'étape 1 n'existe pas déjà, créez-la :

    php bin/console doctrine:database:create

Copier le fichier `Database/symfony.sql` dans votre database

