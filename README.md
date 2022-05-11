# simple migration commands for pimcore

Provide helper commands to perform doctrine migrate per namespace

## Requirements

* Pimcore >= 10.4
* config file in your project `PIMCORE_PROJECT_ROOT . '/config/packages/doctrine-migrations.yaml'`


## Installation

Require the bundle

```bash
composer require gal-digital-gmbh/pimcore-simple-migrate
```

## Commands

```console
php bin/console migrate

Please select namespace
  [0] all
  [1] App\Migrations
  [2] App\NamespaceX\Migrations

```

Rollback one migration of namespace
```console
php bin/console migrate:prev

Please select namespace
  [0] App\Migrations
  [1] App\NamespaceX\Migrations

```

Migrate one migration of namespace
```console
php bin/console migrate:next

Please select namespace
  [0] App\Migrations
  [1] App\NamespaceX\Migrations

```

Generate new migration class in your selected namespace
```console
php bin/console migration:generate

Please select namespace
  [0] App\Migrations
  [1] App\NamespaceX\Migrations

```
