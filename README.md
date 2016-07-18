Integrating the "rightsservice" with Splash
===========================================

This package is part of the Mouf PHP framework and contains the @Right annotation that integrates the [Splash MVC framework](http://mouf-php.com/packages/mouf/mvc.splash/index.md) with the [RightsService](http://mouf-php.com/packages/mouf/security.rightsservice/README.md).

This package provides one useful filter:

The **@Right** annotation
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


Composite rights: the **@AndRight** and **@OrRight** annotations
----------------------------------------------------------------

Occasionally, you might want to check if a user has 2 rights (**and**), or one of two rights (**or**).

To do this, instead of passing a string to the @Right annotation, you can pass a @AndRight or a @OrRight annotation.

For instance, to check that a user has both the CAN_DO_THIS and CAN_DO_THAT rights, you should use:

```php
/**
 * An action that requires to have both the "CAN_DO_THIS" and "CAN_DO_THAT" right.
 *
 * @URL /admin
 * @Right(@AndRight({@Right("CAN_DO_THIS"), @Right("CAN_DO_THAT")}))
 */
public function index() { ... }
```

If instead, you want to check that a user has one right amongst many, you would use the @OrRight:

```php
/**
 * An action that requires to have either the "CAN_DO_THIS" or "CAN_DO_THAT" right.
 *
 * @URL /admin
 * @Right(@OrRight({@Right("CAN_DO_THIS"), @Right("CAN_DO_THAT")}))
 */
public function index() { ... }
```

You can also combine @AndRight and @OrRight annotations as long as the top-most annotation is a @Right.
Also, if you need to combine complex rights, you should probably start to question your right system and refactor it. @AndRight and @OrRight should really be used sparsely.
