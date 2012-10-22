# Suin\CSRFFirewallBundle for Symfony2

This is a firewall bundle which protects your Symfony2 websites form CSRF(cross site request forgery) attack. This bundle works almost automatically, so you don't need to consider CSRF protections for each pages.

## Features

* All post-method-form will be protected from CSRF(even if you don't use Symfony Form's anti-CSRF)

## Requirements

* PHP 5.3 or later

## Installation

Add `suin/symfony2-csrf-firewall-bundle` to your composer.json:

```json
{
    "require": {
        "suin/symfony2-csrf-firewall-bundle":">=1.0.0"
    }
}
```

Execute composer to install:

```
$ php composer.phar update suin/symfony2-csrf-firewall-bundle
```

Add `Suin\CSRFFirewallBundle\SuinCSRFFirewallBundle` to your `app/AppKernel.php`:

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Suin\CSRFFirewallBundle\SuinCSRFFirewallBundle(),
        );
    ...
...
```

## How it works

SuinCSRFFirewallBundle always check CSRF token at all POST method. If the token was not given or an invalid token was given, SuinCSRFFirewallBundle returns 404 Bad Request response to clienet and stops process before the action execution. (exactlly this filter works on `kernel.controller` event.)

At response (exactlly on `kernel.response` event), SuinCSRFFirewallBundle finds all post method forms in the response HTML and automatically embeds CSRF tokens to form to form.

## Options

### How to disable CSRF check at a specific action

With adding `@CSRF(check=false)` annotation to a specific action method, you can disable CSRF check at the action.

```php
<?php

namespace Acme\YourBundle\Controller;

use Suin\CSRFFirewallBundle\Annotations\CSRF;

class FooController extends Controller
{
    ...

    /**
     * @Route("/{id}/create/", name="create")
     * @Template()
     * @CSRF(check=false)
     */
    public function createAction($id)
    {
    	...
        return [];
    }

    ...
...
    
```

### How to change token key name

The default token key name is `__token__`. If you need to change the token key name, edit `app/config/parameters.yml` and define ``.

```yml
parameters:
    suin.csrf_firewall.token_name: your_favorite_token_name
```

## License

MIT License, see LICENSE


