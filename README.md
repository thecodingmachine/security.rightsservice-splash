Integrating the "rightsservice" with Splash
===========================================

This package is part of the Mouf PHP framework and contains the @Right annotation that integrates the [Splash MVC framework](http://mouf-php.com/packages/mouf/mvc.splash/index.md) with the [RightsService](http://mouf-php.com/packages/mouf/security.rightsservice/README.md).

This package provides one useful filter:

The <b>@Right</b> annotation
------------------------------------

This filter can be used in any action. If you put this annotation, the user will be denied access
if he does not possess the specified right.

```php
/**
 * A sample default action that requires to have the "ACCESS_ADMIN_RIGHT" right.
 *
 * @URL /admin
 * @Right("ACCESS_ADMIN_RIGHT")
 */
public function index() { ... }
```

The <b>@Right</b> annotation requires an instance of `ForbiddenMiddleware` to exist. The name of the instance must be `ForbiddenMiddleware::class`.
If your `ForbiddenMiddleware` instance is not named `ForbiddenMiddleware::class` (or if you want to use several ForbiddenMiddleware instances,
you can specify the instance of middleware to use in parameter of the annotation:

```php
/**
 * A sample default action that requires to have the "ACCESS_ADMIN_RIGHT" right.
 *
 * @URL /admin
 * @Right(name="ACCESS_ADMIN_RIGHT",instance="myForbiddenMiddleware")
 */
public function index() { ... }
```
