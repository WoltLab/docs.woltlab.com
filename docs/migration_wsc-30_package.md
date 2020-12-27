---
title: Migrating from WSC 3.0 - Package Components
sidebar: sidebar
permalink: migration_wsc-30_package.html
folder: migration/wsc-30
---

## Cronjob Scheduler uses Server Timezone

The execution time of cronjobs was previously calculated based on the coordinated universal time (UTC). This was changed in WoltLab Suite 3.1 to use the server timezone or, to be precise, the default timezone set in the administration control panel.

## Exclude Pages from becoming a Landing Page

Some pages do not qualify as landing page, because they're designed around specific expectations that aren't matched in all cases. Examples include the user control panel and its sub-pages that cannot be accessed by guests and will therefore break the landing page for those. While it is somewhat to be expected from control panel pages, there are enough pages that fall under the same restrictions, but aren't easily recognized as such by an administrator.

You can exclude these pages by adding `<excludeFromLandingPage>1</excludeFromLandingPage>` (case-sensitive) to the relevant pages in your `page.xml`.

### Example Code

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/page.xsd">
  <import>
    <page identifier="com.example.foo.Bar">
      <!-- ... -->
      <excludeFromLandingPage>1</excludeFromLandingPage>
      <!-- ... -->
    </page>
  </import>
</data>
```

## New Package Installation Plugin for Media Providers

Please refer to the documentation of the [`mediaProvider.xml`][package_pip_media-provider] to learn more.

## Limited Forward-Compatibility for Plugins

Please refer to the documentation of the [`<compatibility>`](package_package-xml.html#compatibility) tag in the `package.xml`.

{% include links.html %}
