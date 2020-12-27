---
title: Migrating from WSC 5.3 - JavaScript
sidebar: sidebar
permalink: migration_wsc-53_javascript.html
folder: migration/wsc-53
---

## `WCF_CLICK_EVENT`

For event listeners on click events, `WCF_CLICK_EVENT` is deprecated and should no longer be used.
Instead, use `click` directly:

```javascript
// before
element.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));

// after
element.addEventListener('click', (ev) => this._click(ev));
```
