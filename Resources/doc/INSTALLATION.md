# Installation

## Get the bundle using composer

Add EdgarEzTFABundle by running this command from the terminal at the root of
your eZPlatform project:

```bash
composer require edgarez/tfabundle
```


## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new EdgarEz\TFABundle\EdgarEzTFABundle(),
        // ...
    );
}
```

## Configure bundle

```yaml
# app/config/config.yml
edgar_ez_tfa:
    system:
        acme_site: # TFA is activated only for this siteaccess
            provider: email # TFA provider type
            providers:
                email:
                    from: no-spam@your.mail # email provider sender mail
```

Note:
 
* don't activate TFA for all site, specially for back-office siteaccess : we are working to enable TFA for eZ Platform Back-Office 

## Routing

```yaml
# app/config/routing.yml
tfa_auth:
    resource: "@EdgarEzTFABundle/Resources/config/routing.yml"
    prefix:   /_tfa
```

## Role and policy

To enable TFA for your users, add policy tfa/* to user or role
