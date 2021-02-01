### Specifications
#### Requirements
1. [Git 2.16.2 and higher](https://git-scm.com/downloads)
2. [Docker 17.03 and higher](https://www.docker.com/community-edition)
3. [Docker Compose](https://docs.docker.com/compose/)

#### Installation
- `docker-compose up -d`
- `docker-compose exec php bash -c 'composer install'`
- `docker-compose exec php bash -c 'bin/console doctrine:migrations:migrate -n'`
- `docker-compose exec php bash -c 'bin/console doctrine:fixtures:load -n'`
- Open http://localhost:8080
