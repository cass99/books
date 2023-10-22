Books Api
=====

# Installation

Install dependencies:

```bash
composer install
```

Add your database creadentials inside .env file and run:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

You can insert dummy data to database using:

```bash
php bin/console doctrine:fixtures:load
```

Init api using:

```bash
symfony server:start
```

Root to API:

/api/doc