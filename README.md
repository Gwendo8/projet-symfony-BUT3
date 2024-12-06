# Documentation - Projet Symfony BUT3

1. Installation des dépendances

Composer :
composer install

Bundle des Fixtures :
composer require --dev doctrine/doctrine-fixtures-bundle

Webpack Encore :
npm install --save-dev @symfony/webpack-encore

Compilation des assets :
npm run dev
npm run build

2. Création et configuration de la base de données

Vérification de la configuration :
DATABASE_URL="mysql://root@127.0.0.1:3306/projet-symfony?&charset=utf8mb4"

Création de la base de données :
php bin/console doctrine:database:create

Mise en place du schéma :
php bin/console doctrine:migrations:migrate

3. Injection des données dans la base de données
   php bin/console doctrine:fixtures:load

4. Lancement du projet
   symfony server:start

- Gestion des Stimulus pour le Panier et la Modification

5. Informations

Stimulus : Ajout au panier

    •	Fonctionnalité : Lorsqu’un utilisateur clique sur le bouton “Ajouter au panier”, le compteur du panier s’incrémente correctement dans la console.
    •	Problème :
    •	La mise à jour du compteur ne se reflète pas dynamiquement dans le DOM.
    •	Pour voir le compteur mis à jour, il faut :
    •	Soit rafraîchir la page.
    •	Soit constater que le compteur s’incrémente en dessous du bouton “Ajouter au panier”, au lieu de mettre à jour le compteur a coté de l’icône de panier.

Stimulus : Modification

    •	Fonctionnalité : La logique de modification fonctionne correctement dans la console.
    •	Problème :
    •	Comme pour l’ajout au panier, les modifications ne se mettent pas à jour dynamiquement dans le DOM.
    •	Il est nécessaire de rafraîchir la page pour voir les changements.
