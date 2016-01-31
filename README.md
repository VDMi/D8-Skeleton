# Composer skeleton for Drupal projects

This project template provides a way to kickstart a Drupal project using [Composer](https://getcomposer.org/) dependencies, it automatically generates a base profile, theme and config.
After following the instructions shown with ```create-project``` you have a working Drupal site ready for development.

Based on the work of [Drupal Composer](http://drupal-composer.org/).

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar) for your setup.

After that you can create the project:

```
composer create-project vdmi/d8-skeleton --stability dev --no-interaction destination
```

```destination``` is the target directory in which the project will be generated.

After the ```composer create-project```, enter the ```destination``` directory and follow the instructions given by Composer.
