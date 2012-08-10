Description
===========

This bundle lets you use the [Kint](https://github.com/raveren/kint) library in your Twig templates.

This bundle adds a new Twig **kint()** function which is a replacement for the Twig **dump()** function. Extremely easy to use but very powerful. Some of its features:

  * Much more elegant and readable output - structured, collapsible and escaped.
  * The name of the twig variable is displayed.
  * Accepts any number of parameters in one call and groups them for you to see what was dumped in different iterations. 
  * Handles recursive variables.
  * Much more information is displayed about the variable in many cases:
    - static properties of the dumped objects class;
    - specific types of data are recognized and displayed in a custom way (eg. JSON, XML strings);
    - if a resource variable is of an opened file, the file name is displayed and much more...
  * Complex variables are dumped with a fixed nested depth so that it doesn't hang up your browser for enormous objects.


Installation
============

## Using Composer (for Symfony 2.1)

Add the Kint Bundle in your composer.json file:

```js
{
    "require": {
        "Cg/KintBundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update Cg/KintBundle
```

Composer will install the bundle and Kint in your project's `vendor` directory.

## Using Deps (for Symfony 2.0)


Add this to your `Deps` file: 

``` ini
[Kint]
    git=http://github.com/raveren/kint.git
    target=Kint
[KintBundle]
    git=http://github.com/barelon/CgKintBundle.git
    target=bundles/Cg/KintBundle
```

then run `./bin/vendors install`

Add the `Kint` and `Cg` namespaces to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Kint' => __DIR__.'/../vendor/Kint',
    'Cg' => __DIR__.'/../vendor/bundles',
));
```


## Update your AppKernel (for both Symfony 2.1 and 2.0)

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Cg\KintBundle\CgKintBundle(),
    );
}
```

Usage
=====

Within any Twig template use

```
{{kint(var1,var2,...)}}
```

This will dump on screen the value of these Twig variables. They will be shown in a pretty format, with a collapsable hierarchical view. Click on any variable name to open or collapse one level or click the `+`  and `-` signs to open or collapse all levels.

If you don´t include any variable, like this:

```
{{kint()}}
```

then the whole Twig Context with all its variables will be shown.

Kint will only show this output id debug is true (usally this is the case for the dev environment, while it is false in the prod environment)

Configuration
=============

In your `app/config/config.yml` file include

```yml
cg_kint:
    enabled:          true
    nesting_depth:    5
    string_length:    60
```

- The `enabled` parameter defines if kint output is enabled or not. Set this to false and Kint will not output anything, not even in debug mode.
- The `nesting_depth` parameter defines the maximum depth of nesting in object/array variables that Kint will show. Use 0 for infinite depth. Kint will recognize recursion in variables and will not hang your browser.
- The `string_length` parameter defines the maximum lenth of strings shown. If a string is longer than that it will be shown truncated with a link to see it fully.

All these parameters are optional. If you don´t include them they will take the value shown above.

License
=======

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
=====

KintBundle has been created by [Carlos Granados](https://github.com/barelon).

Kint was created by [Rokas Šleinius](https://github.com/raveren).

See also the list of [contributors](https://github.com/barelon/cgkintbundle/contributors).

Reporting an issue or a feature request
=======================================

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/barelon/cgkintbundle/issues).
