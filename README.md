# Common PHP8 Patterns and Constructs

[![Build Status](https://travis-ci.com/phpugph/components.svg?branch=main)](https://travis-ci.com/phpugph/components)
[![Coverage Status](https://coveralls.io/repos/github/phpugph/components/badge.svg?branch=main)](https://coveralls.io/github/phpugph/components?branch=main)
[![Latest Stable Version](https://poser.pugx.org/phpugph/components/v/stable)](https://packagist.org/packages/phpugph/components)
[![Total Downloads](https://poser.pugx.org/phpugph/components/downloads)](https://packagist.org/packages/phpugph/components)
[![Latest Unstable Version](https://poser.pugx.org/phpugph/components/v/unstable)](https://packagist.org/packages/phpugph/components)
[![License](https://poser.pugx.org/phpugph/components/license)](https://packagist.org/packages/phpugph/components)

## Install

```bash

composer install phpugph/components

```

## Components

 - Curl - cURL wrapper class that helps making cUrl calls easier
 - Data - Manages data structs of all kinds. Models, Collections and Registry objects are covered here
 - Event - Similar to JavaScript Events. Covers basic and wildcard events.
 - Helper - Miscellaneous traits used to add class features
 - Http - Deals with Routers, Request, Response and Middleware
 - i18n - Covers Language translations and timezone conversions
 - Image - Dynamic Image processor
 - Profiler - Assists with troubleshooting code
 - Resolver - IoC to manage dependency injections

----

<a name="contributing"></a>
# Contributing

Please be aware that master branch contains all edge releases of the current version. Please check the version you are working with and find the corresponding branch. For example `v1.1.1` can be in the `1.1` branch.

Bug fixes will be reviewed as soon as possible. Minor features will also be considered, but give me time to review it and get back to you. Major features will **only** be considered on the `master` branch.

1. Fork the Repository.
2. Fire up your local terminal and switch to the version you would like to
contribute to.
3. Make your changes.
4. Always make sure to sign-off (-s) on all commits made (git commit -s -m "Commit message")

## Making pull requests

1. Please ensure to run [phpunit](https://phpunit.de/) and
[phpcs](https://github.com/squizlabs/PHP_CodeSniffer) before making a pull request.
2. Push your code to your remote forked version.
3. Go back to your forked version on GitHub and submit a pull request.
4. All pull requests will be passed to [Travis CI](https://travis-ci.com/github/phpugph/components) to be tested. Also note that [Coveralls](https://coveralls.io/github/phpugph/components) is also used to analyze the coverage of your contribution.
