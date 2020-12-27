---
title: Style Package Installation Plugin
sidebar: sidebar
permalink: package_pip_style.html
folder: package/pip
parent: package_pip
---

Install styles during package installation.

The `style` package installation plugins expects a relative path to a `.tar` file, a`.tar.gz` file or a `.tgz` file.
Please use the ACP's export mechanism to export styles.

## Example in `package.xml`

```xml
<instruction type="style">style.tgz</instruction>
```
