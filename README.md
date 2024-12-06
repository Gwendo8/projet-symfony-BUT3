Documentation - Projet Symfony BUT3 : Mini Site E-commerce

1. Sujet du Projet

1.1. Description

Le projet consiste à développer une plateforme E-commerce en ligne permettant aux utilisateurs de :
• Acheter des produits organisés par catégories.
• Gérer leurs paniers et passer des commandes.
• Accéder à des fonctionnalités administratives pour la gestion des utilisateurs, produits et commandes.


2. Fonctionnalités

2.1. Gestion des produits

    •	CRUD Produits : Formulaires pour afficher, créer et modifier des produits.
    •	Flash Messages : Retour visuel pour les actions (ajout, modification, suppression).
    •	Protection des données : Les produits associés à des commandes ne peuvent pas être supprimés.

2.2. Gestion des utilisateurs et commandes

    •	Création d’une interface administrateur pour :
    •	Lister les utilisateurs et produits.
    •	Ajouter, modifier et supprimer des produits.
    •	Gérer les quantités disponibles.
    •	Afficher les commandes.
    •	Système de connexion sécurisé :
    •	Gestion des accès aux pages administratives.
    •	Mot de passe haché dans la base de données.

2.3. Tableau de bord

    •	Visualisation des indicateurs :
    •	Nombre total de produits par catégorie.
    •	5 dernières commandes.
    •	Ratio de disponibilité des produits (en stock, en rupture, en précommande).
    •	Montant total des ventes mensuelles sur les 12 derniers mois.

2.4. Panier d’achat et commandes

    •	Fonctionnalités du panier :
    •	Ajout de produits, modification des quantités et passage de commandes.
    •	Mise à jour des stocks lors de la validation d’une commande.
    •	Stimulus : Mise à jour dynamique du panier sans rechargement de la page.

2.5. Pages dynamiques

    •	Recherche dynamique :
    •	Recherche des produits avec Autocomplete.
    •	Affichage d’aperçus des produits.
    •	Formulaires dynamiques :
    •	Ajout de cartes bancaires avec validation en temps réel et sans rechargement.
    •	Formulaires imbriqués pour gérer plusieurs cartes.

3. Installation et Configuration

3.1. Installation des dépendances

Composer :
composer install

Bundle des Fixtures :
composer require --dev doctrine/doctrine-fixtures-bundle

Webpack Encore :
npm install --save-dev @symfony/webpack-encore

Compilation des assets :
npm run dev
npm run build

3.2. Configuration de la base de données

    1.	Vérification de la configuration :

DATABASE_URL="mysql://root@127.0.0.1:3306/projet-symfony?&charset=utf8mb4"

    2.	Création de la base :

php bin/console doctrine:database:create

    3.	Migration des schémas :
    php bin/console doctrine:migrations:migrate

3.3. Injection des données
php bin/console doctrine:fixtures:load

3.4. Lancement du serveur
symfony server:start

4. Problème

4.1. Stimulus : Ajout au panier

Lorsqu’un utilisateur clique sur “Ajouter au panier”, le compteur du panier s’incrémente dans la console.
• Problème :
La mise à jour ne se reflète pas dynamiquement dans le DOM. 

4.2. Stimulus : Modification

Les modifications fonctionnent correctement dans la console.
• Problème :
Comme pour l’ajout au panier, les changements nécessitent un rafraîchissement pour apparaître dans le DOM.

4.3. LiveComponent : Ajout de cartes bancaires

    •	Problème :
Les boutons du composant LiveComponent ne fonctionnent pas actuellement.


