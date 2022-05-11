# simple migration commands for pimcore

Provide helper commands to perform doctrine migrate per namespace

## Requirements

Pimcore >= 10.1

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
