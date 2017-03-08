# Extend

You are able to extend this bundle with adding new TFA provider.

# Provider Class

```php
class <Your>Provider extends ProviderAbstract implements ProviderInterface
{

    public function requestAuthCode(Request $request)
    {
        /**
         * Generate auth code
         * return authentication page uri
         */

        return $redirectUrl;
    }
}
```

# Provider Controller

```php
class <Your>Controller extends Controller
{
    public function authAction(Request $request)
    {
        /**
         * Send to user auth code
         * or/and simply display iininterface to check auth code 
         */
    }

    public function checkAction(Request $request)
    {
        /**
         * Check auth code
         * Redirect user to siteaccess view if OK
         * if NOK, redirect user to auth form
         */
    }
}
```

# settings

```yaml
# Resources/config/services.yml
services:
    edgareztfa.provider.<your_provider>:
        class: %edgareztfa.provider.<your_provider>.class%
        tags:
            - { name: edgareztfa.provider, alias: <your_provider> }
```

Replace <your_provider> with your provider key ('email' is already used)

Example of routing configuratioon

```yaml
# Resources/config/routing.yml
tfa_<your_provider>_auth:
    path: /auth
    methods: [GET]
    defaults: { _controller: edgareztfa.<your_provider>.controller.auth:authAction }

tfa_<your_provider>_auth_check:
    path: /check
    methods: [POST]
    defaults: { _controller: edgareztfa.<your_provider>.controller.auth_form:checkAction }
```

Add your routing to global routing

```yaml
# app/config/routing.yml
tfa_<your_provider>:
    resource: '@<your_bundle>/Resources/config/routing.yml'
    prefix: /_tfa/<your_provider>
```


