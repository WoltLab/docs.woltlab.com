---
title: Migrating from WSC 5.3 - PHP
sidebar: sidebar
permalink: migration_wsc-53_php.html
folder: migration/wsc-53
---

## Minimum requirements

The minimum requirements have been increased to the following:

- **PHP:** 7.2.24
- **MySQL:** 5.7.31 or 8.0.19
- **MariaDB:** 10.1.44

Most notably PHP 7.2 contains usable support for scalar types by the addition of nullable types in PHP 7.1 and parameter type widening in PHP 7.2.

It is recommended to make use of scalar types and other newly introduced features whereever possible.
Please refer to the PHP documentation for details.
