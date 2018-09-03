<div align="center">
    <a href="#"><img src="logo-linna-96.png" alt="Linna Logo"></a>
</div>

<br/>

<div align="center">
    <a href="#"><img src="logo-framework.png" alt="Linna framework Logo"></a>
</div>

<br/>

<div align="center">

[![Build Status](https://travis-ci.org/linna/framework.svg?branch=master)](https://travis-ci.org/linna/framework)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linna/framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linna/framework/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/linna/framework/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/linna/framework/?branch=master)
[![StyleCI](https://styleci.io/repos/41168432/shield?branch=master&style=flat)](https://styleci.io/repos/41168432)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat)](https://github.com/php-pds/skeleton)

</div>

# About this framework
Started as a project for learning the modern PHP, it has become a set of simple and elegant tools for creating web applications.

## Quality
The development of Framework is done trying to get PHP best pratices always in mind.<br/>If you wish deepen PHP best pratices you can start from [phptherightway](http://www.phptherightway.com/)

### All code is:
   * Tested with [phpunit](https://github.com/sebastianbergmann/phpunit) and [infection](https://github.com/infection/infection)
   * Analyzed with [phpstan](https://github.com/phpstan/phpstan) and [phan](https://github.com/phan/phan/)
   * Commented, ready for [phpDocumentor](https://www.phpdoc.org/)
   * Written applying the five [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design)) principles

## Production environment
At moment, code, isn't utilized in production environment, Hope in future.

# Require

   * PHP >= 7.1
   * PDO extension (optional)
   * Memcached extension (optional)
   * Mongodb extension (optional)

# Installation
With composer:
```
composer require linna/framework
```

# Features
 
   * Model View Controller
   * Session Management
   * Login and User/Permission access control
   * Rest Router
   * Dependency Injections
   * Wrappers for data base
   * Implementation for PSR-4 Autoloader, PSR-11 Container and PSR-16 Simple Cache

# Documentation 
For more details please see the [user guide (soon)](https://linna.tools/docs/current/) or the [api (incomplete)](https://linna.tools/api/current/) and read our [licence](https://github.com/linna/framework/blob/master/LICENSE.md)

# Contributing
Please see [CONTRIBUTING.md](https://github.com/linna/framework/blob/master/CONTRIBUTING.md).
