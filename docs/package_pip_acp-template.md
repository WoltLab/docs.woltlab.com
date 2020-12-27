---
title: ACP Template Installation Plugin
sidebar: sidebar
permalink: package_pip_acp-template.html
folder: package/pip
parent: package_pip
---

Add templates for acp pages and forms by providing an archive containing the template files.

{% include callout.html content="You cannot overwrite acp templates provided by other packages." type="warning" %}


## Archive

The `acpTemplate` package installation plugins expects a `.tar` (recommended) or `.tar.gz` archive.
The templates must all be in the root of the archive.
Do not include any directories in the archive.
The file path given in the `instruction` element as its value must be relative to the `package.xml` file.


## Attributes

### `application`

The `application` attribute determines to which application the installed acp templates belong and thus in which directory the templates are installed.
The value of the `application` attribute has to be the abbreviation of an installed application.
If no `application` attribute is given, the following rules are applied:

- If the package installing the acp templates is an application, then the templates will be installed in this application's directory.
- If the package installing the acp templates is no application, then the templates will be installed in WoltLab Suite Core's directory.


## Example in `package.xml`

```xml
<instruction type="acpTemplate" />
<!-- is the same as -->
<instruction type="acpTemplate">acptemplates.tar</instruction>

<!-- if an application "com.woltlab.example" is being installed, the following lines are equivalent -->
<instruction type="acpTemplate" />
<instruction type="acpTemplate" application="example" />
```
