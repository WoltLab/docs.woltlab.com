---
title: Migrating from WSC 5.3- Templates and Languages
sidebar: sidebar
permalink: migration_wsc-53_templates.html
folder: migration/wsc-523
---

## `{csrfToken}`

Going forward, any uses of the `SECURITY_TOKEN_*` constants should be avoided.
To reference the CSRF token (“Security Token”) within templates, the `{csrfToken}` template plugin was added.

Before:

```smarty
{@SECURITY_TOKEN_INPUT_TAG}
{link controller="Foo"}t={@SECURITY_TOKEN}{/link}
```

After:

```smarty
{csrfToken}
{link controller="Foo"}t={csrfToken type=url}{/link} {* The use of the CSRF token in URLs is discouraged.
                                                        Modifications should happen by means of a POST request. *}
```

The `{csrfToken}` plugin was backported to WoltLab Suite 5.2 and higher, allowing compatibility with a large range of WoltLab Suite branches.
See [WoltLab/WCF #3612](https://github.com/WoltLab/WCF/pull/3612) for details.
