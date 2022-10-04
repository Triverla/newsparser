# News Parser Using Symfony, Docker & RabbitMq

## Configuration
``cd docker`` from application root
Get docker up and running
``docker compose up``

## Run Migrations
``php bin/console make:migration`` \
``php bin/console doctrine:migrations:migrate``

## Run Seeder
``php bin/console doctrine:fixtures:load``

## Test Login Credentials
Admin
``Email: admin@newsparser.com`` \
``Password: password``

Moderator
``Email: moderator@newsparser.com`` \
``Password: password``

PS: Only Admins can delete posts

## Run Cli command to parse news
ssh into docker container
``docker ps -a`` to get containerID \
``docker exec -it containerID /bin/bash ``

then run
``php bin/console parse:news `` to parse news

## Run Queue
``php bin/console messenger:consume async -vv``

Good to go!
