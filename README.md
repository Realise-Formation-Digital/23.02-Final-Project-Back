# 23.02-Final-Project-Back

## Déploiement en production (pour équipe front-end)

1. Cloner le projet
2. Se déplacer dans la branche development (provisoire)
3. Lancer la commande
    > docker-compose -f docker-compose.prod.yml up -d --build

4. Pour arrêter les containers, lancer la commande:
    > docker-compose -f docker-compose.prod.yml down


## Documentation

La documentation se trouve à l'adresse:

http://localhost:8660/doc

## Déploiement en développement (pour équipe back-end)

1. Cloner le projet
2. Se déplacer dans la branche voulue
3. Lancer la commande
   > docker-compose up -d

4. Ajouter les dépendances et les autoload
    > cd www

    > composer install

    > composer dump-autoload

5. Pour arrêter les containers, lancer la commande:
   > docker-compose down
