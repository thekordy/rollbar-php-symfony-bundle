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

## Requirements

This bundle depends on [symfony/monolog-bundle](https://github.com/symfony/monolog-bundle).

## Installation
1. Add `Rollbar for Symfony` with composer: `composer require rollbar/rollbar-php-symfony3-bundle`
2. Register `Rollbar\Symfony\RollbarBundle` in `AppKernel::registerBundles()` **after** registering the `MonologBundle` (`new Symfony\Bundle\MonologBundle\MonologBundle()`).

```php

    public function registerBundles()
    {
        $bundles = [
            // ...
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            // ...
            new \SymfonyRollbarBundle\SymfonyRollbarBundle(),
            // ...
        ];

        return $bundles;
    }
    
```

3. Configure Rollbar and Monolog in your `app/config.yml` or `app/config_*.yml`.

```yaml

rollbar:
  enable: true
  config:
    access_token: YourAccessToken
    environment: YourEnvironmentName
    
monolog:
  handlers:
    rollbar:
      type: service
      id: Rollbar\Monolog\Handler\RollbarHandler
    
```

## Usage

### Exception reporting

Symfony 3 exceptions will be reported to Rollbar automatically after you install and configure the bundle.

### Manual reporting

This bundle injects itself into the Monolog loggers. Thanks to this, all of the Monolog logs will be automatically passed to Rollbar as well.

All you need to do is obtain the `LoggerInterface` implementation from the service container.

```php

  namespace AppBundle\Controller;
  
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\HttpFoundation\Request;
  use Psr\Log\LoggerInterface;
  
  class DefaultController extends Controller
  {
      /**
       * @Route("/", name="homepage")
       */
      public function indexAction(Request $request, LoggerInterface $logger)
      {
          $logger->error('Test info with person data');
          
          // replace this example code with whatever you need
          return $this->render('default/index.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
          ]);
      }
  }

```

## Configuration

You can see all of the Rollbar configuration options [here](https://github.com/rollbar/rollbar-php#configuration-reference).

All of them can be configured by nesting them in `rollbar.config` array, i.e.:

```yaml

rollbar:
  enable: true
  config:
    access_token: YourAccessToken
    environment: YourEnvironmentName
    scrub_fields: [password, password_confirmation, credit_card_number]
    
```

### `person` configuration option

By default, this bundle fetches the user data with `$container->get('security.token_storage')->getToken()->getUser()`. However, you can hardcode your own person data here. Although this might be used rarely, if you want to pass user data to Rollbar, you probably want to set up `person_fn` (see below).

### `person_fn` configuration option

*Note:* data returned by the `person_fn` callable will overwrite any data provided in `person` config or fetched from Symfony's `$container->get('security.token_storage')->getToken()->getUser()`.

You can provide your own logic for retrieving user data with the `person_fn` configuration option. The value should be a PHP callable returning an array of data in the `person` format, i.e.:

```yaml

rollbar:
    enable: true
    config:
        access_token: YourAccessToken
        environment: YourEnvironmentName
        person_fn: '\Example\UserData::personFn'
        
```

```php
namespace Example;

class UserData
{
  
  public static function personFn()
  {
    return array(
      'id' => '444'
    );
  }
}
```

## Help / Support

If you run into any issues, please email us at [support@rollbar.com](mailto:support@rollbar.com)

You can also find us on IRC: [#rollbar on chat.freenode.net](irc://chat.freenode.net/rollbar)

For bug reports, please [open an issue on GitHub](https://github.com/rollbar/rollbar-php-symfony3-bundle/issues/new).

## Special thanks

The original author of this package is [@OxCom](https://www.oxcom.me). This is a fork and continuation of efforts.

## Testing

You can set up a Rollbar Access Token for testing in `tests/phpunit.xml`.
Tests are in `tests`.
To run the tests: `composer test`
To fix code style issues: `composer fix`

## Disclaimer

This plugin is a community-driven contribution.

[![Rollbar](https://d26gfdfi90p7cf.cloudfront.net/rollbar-badge.144534.o.png)](https://rollbar.com/)
