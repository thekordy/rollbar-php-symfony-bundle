# Rollbar for Symfony 3
[![codecov](https://codecov.io/gh/rollbar/rollbar-php-symfony3-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/rollbar/rollbar-php-symfony3-bundle)
[![Build Status](https://travis-ci.org/rollbar/rollbar-php-symfony3-bundle.svg?branch=master)](https://travis-ci.org/rollbar/rollbar-php-symfony3-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Rollbar full-stack error tracking for Symfony 3

## Description
Rollbar collects errors that happen in your application, notifies you, and analyzes them so you can debug and fix them.

This plugin integrates Rollbar into your Symfony 3 installation.

Find out [how Rollbar can help you decrease development and maintenance costs](https://rollbar.com/features/).

See [real companies improving their development workflow thanks to Rollbar](https://rollbar.com/customers/).

## Installation
1. Add SymfonyRollbarBundle with composer: `composer require oxcom/symfony-rollbar-bundle`
2. Register SymfonyRollbarBundle in AppKernel::registerBundles()

```php

    public function registerBundles()
    {
        $bundles = [
            // ...
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
            new \SymfonyRollbarBundle\SymfonyRollbarBundle(),
            // ...
        ];

        return $bundles;
    }
    
```

3. Configure Rollbar in `app/config_*.yml`.

```yaml

symfony_rollbar:
  enable: true
  rollbar:
    access_token: YourAccessToken
    environment: YourEnvironmentName
    
```

## Configuration

You can see all of the Rollbar configuration options [here](https://github.com/rollbar/rollbar-php#configuration-reference).

All of them can be configure by nesting them in `symfony_rollbar.rollbar` array, i.e.:

```yaml

symfony_rollbar:
  enable: true
  rollbar:
    access_token: YourAccessToken
    environment: YourEnvironmentName
    scrub_fields: [password, password_confirmation, credit_card_number]
    
```

## Help / Support

If you run into any issues, please email us at [support@rollbar.com](mailto:support@rollbar.com)

You can also find us on IRC: [#rollbar on chat.freenode.net](irc://chat.freenode.net/rollbar)

For bug reports, please [open an issue on GitHub](https://github.com/rollbar/rollbar-php-symfony3-bundle/issues/new).

## Special thanks

The original author of this package is [@OxCom](https://www.oxcom.me). This is a fork and continuation of efforts.

## Testing

Tests are in `tests`.
To run the tests: `composer test`
To fix code style issues: `composer fix`

## Disclaimer

This plugin is a community-driven contribution.

[![Rollbar](https://d26gfdfi90p7cf.cloudfront.net/rollbar-badge.144534.o.png)](https://rollbar.com/)
