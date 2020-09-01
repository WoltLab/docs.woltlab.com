---
title: Migrating from WSC 5.2 - Templates and Languages
sidebar: sidebar
permalink: migration_wsc-52_templates.html
folder: migration/wsc-52
---

## `{jslang}`

Starting with WoltLab Suite 5.3 the `{jslang}` template plugin is available.
`{jslang}` works like `{lang}`, with the difference that the result is automatically encoded for use within a single quoted JavaScript string.

Before:

```smarty
<script>
require(['Language', /* … */], function(Language, /* … */) {
    Language.addObject({
        'app.foo.bar': '{lang}app.foo.bar{/lang}',
    });

    // …
});
</script>
```

After:

```smarty
<script>
require(['Language', /* … */], function(Language, /* … */) {
    Language.addObject({
        'app.foo.bar': '{jslang}app.foo.bar{/jslang}',
    });

    // …
});
</script>
```

## Pluralization

The [`{plural}`](view_template-plugins.html#53-plural) template plugin is newly available.
