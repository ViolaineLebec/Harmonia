# Harmonia : développer une plateforme musicale avec Symfony
Une semaine pour construire une application Symfony complète : modèle de données, pages publiques, requêtes personnalisées et back-office d'administration.
Le projet avance par couches, une par jour. Chaque étape s'appuie sur la précédente : un modèle bancal jour 1 se paiera jour 4.
## Contexte du projet
Vous venez d'intégrer une agence web comme développeur back-end junior. Premier projet : Harmonia, une plateforme de streaming musical commandée par un label indépendant qui veut proposer son catalogue à ses abonnés.

Le projet démarre de zéro et vous en avez la charge complète pour la semaine. La cheffe de projet a découpé le travail en cinq étapes et exige que chacune soit terminée avant d'attaquer la suivante : « On a assez donné avec le projet précédent, où on avait commencé les écrans avant de comprendre que les relations étaient fausses. Trois semaines à la poubelle. »

---

### COMPTE-RENDU DE CADRAGE — PROJET HARMONIA

Un utilisateur crée un compte avec une adresse e-mail unique et un pseudo. On garde sa date d'inscription. Mot de passe et rôles sont prévus, mais l'authentification n'est pas au programme de cette semaine.

Le catalogue s'organise autour des artistes : nom de scène, biographie, pays d'origine. Certains artistes récents n'ont pas encore de biographie.

Un artiste publie des albums. Un album appartient à un seul artiste : titre, date de sortie, pochette, et un type parmi album, EP ou single — ces trois valeurs et pas d'autres.

Un album contient des morceaux. Un morceau appartient à un seul album : titre, durée en secondes, numéro de piste, compteur d'écoutes démarrant à zéro, et un indicateur de paroles explicites.

Chaque morceau relève d'un ou plusieurs genres, et un genre concerne beaucoup de morceaux. Un genre a un nom unique et une couleur d'affichage.

Les utilisateurs créent des playlists : nom, description facultative, date de création, indicateur public ou privé. Une playlist appartient à un utilisateur et rassemble plusieurs morceaux ; un morceau peut figurer dans de nombreuses playlists.

Les utilisateurs mettent des morceaux en favoris, dans les deux sens et sans limite.

Enfin, un historique d'écoute : à chaque écoute, on enregistre qui, quel morceau, à quel moment. Le même utilisateur peut réécouter le même morceau autant de fois qu'il veut et chaque écoute compte séparément — c'est ce qui alimentera les statistiques du label.

---

Ce document décrit le métier, pas la technique. À vous d'en déduire les entités, les types et la nature de chaque relation. Attention : toutes les liaisons ne se modélisent pas de la même façon, et l'une d'entre elles ne peut pas être un simple ManyToMany.
## Modalités pédagogiques
Travail en groupe, cinq jours. Un point collectif chaque matin sur l'étape de la veille, une démonstration courte chaque fin de journée.
### JOUR 1 — Fondations : projet, entités, fixtures
Modélisez d'abord sur papier : entités, champs, et une flèche annotée par relation précisant type et sens. Validation par le formateur obligatoire avant tout code.
Créez ensuite le projet avec la CLI Symfony, installez les dépendances, configurez la base dans .env.local (jamais versionné).
Générez les 8 entités au MakerBundle — l'outil pose des questions précises sur le type de relation et le côté propriétaire, lisez-les. Ajoutez les contraintes de validation à la main.
Générez la migration, lisez le SQL produit avant de l'exécuter. Si le SQL vous surprend, le problème est dans vos entités.

Chargez enfin des fixtures réalistes avec DoctrineFixturesBundle.
Commandes à connaître : doctrine:schema:validate, doctrine:mapping:info.
### JOUR 2 — Pages publiques : contrôleurs et Twig
Créez les contrôleurs et les routes des pages de consultation : accueil, liste des artistes, fiche artiste, fiche album, liste des genres, fiche genre.
Utilisez uniquement les méthodes natives du repository (find, findAll, findBy, findOneBy) et le ParamConverter. Aucune requête personnalisée aujourd'hui : c'est volontaire, vous verrez demain où sont les limites.
Côté Twig : un template de base, des blocks, des includes pour les éléments répétés (carte artiste, ligne de morceau), et des filtres pour formater durées et dates.
Gérez les cas d'erreur : un identifiant inexistant renvoie une 404 propre, pas une exception brute.
### JOUR 3 — Requêtes personnalisées : les repositories
Hier, findBy() a suffi. Aujourd'hui vous allez rencontrer ses limites : il ne sait pas chercher « les artistes dont le nom contient bl », ni « les albums sortis après 2020 ». Il faut écrire la requête soi-même, avec le QueryBuilder.
Commencez par la plus simple, la recherche par nom, et faites-la fonctionner avant de passer à la suivante. Cinq méthodes à écrire dans les repositories :
- rechercher les artistes dont le nom contient un texte donné, sans tenir compte de la casse ;
récupérer les albums d'un artiste, triés du plus récent au plus ancien ;
récupérer les dix morceaux les plus écoutés ;
récupérer les albums sortis après une date donnée ;
récupérer les morceaux signalés comme explicites.
Toutes suivent le même squelette : createQueryBuilder(), un ou plusieurs where(), éventuellement orderBy() et setMaxResults(), puis getQuery() et getResult(). Les valeurs variables passent par setParameter(), jamais par concaténation.
Branchez ensuite ces méthodes sur les pages du jour 2 et ajoutez un formulaire de recherche d'artiste sur la page de liste.
Bonus si vous avez terminé : récupérer les morceaux d'un genre donné, ce qui suppose de traverser la relation ManyToMany avec une jointure.
### JOURS 4 ET 5 — Back-office : formulaires et CRUD
Construisez un espace d'administration sous /admin permettant de gérer artistes, albums, morceaux et genres.
Pour chacun : liste, création, modification, suppression. Les formulaires sont construits avec des classes FormType dédiées dans src/Form/, jamais à la main dans Twig.
Points à traiter : champs relationnels (EntityType pour l'artiste d'un album, choix multiple pour les genres d'un morceau), ChoiceType pour le type d'album, validation serveur affichée dans le formulaire, messages flash après chaque opération, confirmation avant suppression, et redirection après enregistrement.
Un même FormType doit servir à la création et à la modification.
Le jour 5 est consacré à la finition : cohérence visuelle du back-office, gestion des cas d'erreur, et bonus si le temps le permet (upload de pochette, pagination des listes, filtres).
Ressources de référence : la documentation Symfony (Doctrine, relations, contrôleurs, Twig, formulaires) et celle de DoctrineFixturesBundle. Le fichier .env.local n'est jamais versionné.
## Critères de performance
### Jour 1 — Fondations
- Les 8 entités sont présentes, nommées au singulier selon les conventions Symfony.
- Chaque type de champ est adapté : pas de date en chaîne, pas de durée en texte.
- Les champs toujours renseignés sont non nullables, les autres non ; les unicités demandées sont portées par la base.
- Les valeurs limitées à une liste fermée sont contraintes, pas laissées libres en texte.
- Chaque relation a le bon type, avec côté propriétaire et côté inverse correctement identifiés et les méthodes des deux côtés.
- L'historique d'écoute permet plusieurs écoutes du même morceau par le même utilisateur, chacune horodatée.
- Les collections sont initialisées dans les constructeurs ; doctrine:schema:validate ne remonte aucune erreur.
- Les migrations s'exécutent sur une base vierge sans erreur.
- Les fixtures se chargent sans erreur et créent au moins 5 artistes, 10 albums, 50 morceaux, 8 genres, 5 utilisateurs, 8 playlists, des favoris et des écoutes.
- Les données sont réalistes et cohérentes entre elles : une écoute n'est pas antérieure à la sortie du morceau.
- Les cas particuliers du cadrage sont représentés : un artiste sans biographie, les trois types d'album, un morceau explicite, une playlist privée.

### Jour 2 — Contrôleurs et Twig
- Toutes les pages annoncées répondent, avec des routes nommées et cohérentes.
- Les contrôleurs restent courts : récupération des données et rendu, pas de logique métier.
- Un identifiant inexistant produit une 404, jamais une exception brute ni une page blanche.
- Les templates héritent d'un layout commun et emploient blocks et includes ; aucun bloc HTML n'est dupliqué d'un template à l'autre.
- Les durées et les dates sont formatées à l'affichage, pas stockées formatées.
- Les liens entre pages sont générés par path(), jamais écrits en dur.

### Jour 3 — Repositories
- Les cinq méthodes demandées sont implémentées dans les repositories, jamais dans les contrôleurs.
- Les requêtes utilisent le QueryBuilder, avec les valeurs variables passées par setParameter() ; aucune concaténation dans la requête.
- La recherche d'artiste fonctionne sur une saisie partielle, quelle que soit la casse, et se comporte correctement sur une recherche vide ou sans résultat.
- Le tri des albums et la limitation aux dix morceaux les plus écoutés produisent bien le résultat attendu.
- Les méthodes sont effectivement utilisées par les pages, et non écrites sans être branchées.

### Jours 4 et 5 — Back-office
- Les quatre entités administrables disposent chacune des cinq opérations, accessibles depuis une navigation d'administration.
- Chaque formulaire est défini dans une classe FormType dédiée, réutilisée pour la création et la modification.
- Les champs relationnels sont fonctionnels : sélection de l'artiste d'un album, sélection multiple des genres d'un morceau.
- La validation serveur bloque les saisies invalides et affiche les messages au niveau des champs concernés, sans perte des valeurs déjà saisies.
- Un message flash confirme chaque
