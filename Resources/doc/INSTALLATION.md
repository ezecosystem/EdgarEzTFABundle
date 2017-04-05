# Installation

## Get the bundle using composer

Add EdgarEzTFABundle by running this command from the terminal at the root of
your eZPlatform project:

```bash
composer require edgarez/tfabundle
```

## Add Doctrine ORM support

edit your ezplatform.yml

```yaml
doctrine:
    orm:
        auto_mapping: true
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

Two providers are natively available:
* email
* sms

### email provider configuration

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

### SMS Provider configuration

Subscribe to OVH SMS Service to obtain api keys

https://www.ovhtelecom.fr/sms/#order-SMS

Go to API key page to generate application and consumer keys

https://api.ovh.com/createToken/


```yaml
# app/config/config.yml
edgar_ez_tfa:
    system:
        acme_site: # TFA is activated only for this siteaccess
            provider: sms # TFA provider type
            providers:
                sms:
                    application_key: <ovh_application_key>
                    application_secret: <ovh_application_secret>
                    consumer_key: <ovh_consumer_key>                    
```

Notes:
* for sms provider, add __phone_number__ text line field type to your User Content Class and add valid phone number to your user content : +<prefix>><phone_number_0_left_trimed>
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
