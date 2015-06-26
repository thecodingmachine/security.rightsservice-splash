Integrating the "rightsservice" with Splash
===========================================

This package is part of the Mouf PHP framework and contains the @RequiresRight annotation that integrates the [Splash MVC framework](http://mouf-php.com/packages/mouf/mvc.splash/index.md) with the [RightsService](http://mouf-php.com/packages/mouf/security.rightsservice/README.md).

This package provides one useful filter:

The <b>@RequiresRight</b> annotation
------------------------------------

This filter can be used in any action. If you put this annotation, the user will be denied access
if he does not possess the specified right.

```php
/**
 * A sample default action that requires to have the "ACCESS_ADMIN_RIGHT" right.
 *
 * @URL /admin
 * @RequiresRight(name="ACCESS_ADMIN_RIGHT")
 */
public function index() { ... }
```

The <b>@RequiresRight</b> annotation requires an instance of [RightsService](http://mouf-php.com/packages/mouf/security.rightsservice/README.md) to exist. The
name of the instance must be "rightsService".
If your RightsService instance is not named "rightsService" (or if you want to use several RightsService instances,
you can specify the instance of UserService to use in parameter of the annotation:

```php
/**
 * A sample default action that requires to have the "ACCESS_ADMIN_RIGHT" right.
 *
 * @URL /admin
 * @RequiresRight(name="ACCESS_ADMIN_RIGHT",instance="myRightService")
 */
public function index() { ... }
```
