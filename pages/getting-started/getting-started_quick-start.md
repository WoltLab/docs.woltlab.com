---
title: Creating a simple package
sidebar: sidebar
permalink: getting-started_quick-start.html
folder: getting-started
---

## Setup and Requirements

This guide will help you to create a simple package that provides a simple test
page. It is nothing too fancy, but you can use it as the foundation for your
next project.

There are some requirements you should met before starting:

- Text editor with syntax highlighting for PHP, [Notepad++](https://notepad-plus-plus.org/) is a solid pick
 - `*.php` and `*.tpl` should be encoded with ANSI/ASCII
 - `*.xml` are always encoded with UTF-8, but omit the BOM (byte-order-mark)
 - Use tabs instead of spaces to indent lines
 - It is recommended to set the tab width to `8` spaces, this is used in the entire software and will ease reading the source files
- An active installation of WoltLab Suite 3
- An application to create `*.tar` archives, e.g. [7-Zip](http://www.7-zip.org/) on Windows

## The package.xml File

We want to create a simple page that will display the sentence "Hello World" embedded
into the application frame. Create an empty directory in the workspace of your choice
to start with.

Create a new file called `package.xml` and insert the code below:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<package name="com.example.test" xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/package.xsd">
	<packageinformation>
		<packagename>Simple Package</packagename>
		<packagedescription>A simple package to demonstrate the package system of WCF</packagedescription>
		<version>1.0.0</version>
		<date>2016-11-27</date>
	</packageinformation>

	<authorinformation>
		<author>YOUR NAME</author>
		<authorurl>http://www.example.com</authorurl>
	</authorinformation>

	<requiredpackages>
		<requiredpackage minversion="3.0.0 Alpha 1">com.woltlab.wcf</requiredpackage>
	</requiredpackages>

	<instructions type="install">
		<instruction type="file" />
		<instruction type="template" />

		<instruction type="page" />
	</instructions>
</package>
```

There is an [entire chapter][package_package-xml] on the package system that explains what the code above
does and how you can adjust it to fit your needs. For now we'll keep it as it is.

## The PHP Class

The next step is to create the PHP class which will serve our page:

1. Create the directory `files` in the same directory where `package.xml` is located
2. Open `files` and create the directory `lib`
3. Open `lib` and create the directory `page`
4. Within the directory `page`, please create the file `TestPage.class.php`

Copy and paste the following code into the `TestPage.class.php`:

```php
<?php
namespace wcf\page;
use wcf\system\WCF;

/**
 * A simple test page for demonstration purposes.
 *
 * @author	YOUR NAME
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class TestPage extends AbstractPage {
  protected $greet = '';

  public function readParameters() {
    parent::readParameters();

    if (isset($_GET['greet'])) $this->greet = $_GET['greet'];
  }

  public function readData() {
    parent::readData();

    if (empty($this->greet)) {
      $this->greet = 'World';
    }
  }

  public function assignVariables() {
    parent::assignVariables();

    WCF::getTPL()->assign([
      'greet' => $this->greet
    ]);
  }
}

```

The class inherits from [wcf\page\AbstractPage](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/page/AbstractPage.class.php), the default implementation of pages without form controls. It
defines quite a few methods that will be automatically invoked in a specific order, for example `readParameters()` before `readData()` and finally `assignVariables()` to pass arbitrary values to the template.

The property `$greet` is defined as `World`, but can optionally be populated through a GET variable (`index.php?test/&greet=You` would output `Hello You!`). This extra code illustrates the separation of data
processing that takes place within all sort of pages, where all user-supplied data is read from within a single method. It helps organizing the code, but most of all it enforces a clean class logic that does not
start reading user input at random places, including the risk to only escape the input of variable `$_GET['foo']` 4 out of 5 times.

Reading and processing the data is only half the story, now we need a template to display the actual content for our page. You don't need to specify it yourself, it will be automatically guessed based on your
namespace and class name, you can [read more about it later][getting-started_quick-start.html#appendixTemplateGuessing].

Last but not least, you must not include the closing PHP tag `?>` at the end, it can cause PHP to break on whitespaces and is not required at all.

## The Template

Navigate back to the root directory of your package until you see both the `files` directory and the `package.xml`. Now create a directory called `templates`, open it and create the file `test.tpl`.

```smarty
{include file='header'}

<div class="section">
	Hello {$greet}!
</div>

{include file='footer'}
```

Templates are a mixture of HTML and Smarty-like template scripting to overcome the static nature of raw HTML. The above code will display the phrase `Hello World!` in the application frame, just as any other
page would render. The included templates `header` and `footer` are responsible for the majority of the overall page functionality, but offer a whole lot of customization abilities to influence their behavior and appearance.

## The Page Definition

The package now contains the PHP class and the matching template, but it is still missing the page definition. Please create the file `page.xml` in your project's root directory, thus on the same level as the `package.xml`.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/vortex/page.xsd">
	<import>
		<page identifier="com.example.test.Test">
			<controller>wcf\page\TestPage</controller>
			<name language="en">Test Page</name>
			<pageType>system</pageType>
		</page>
	</import>
</data>
```

You can provide a lot more data for a page, including logical nesting and dedicated handler classes for display in menus.

## Building the Package

If you have followed the above guidelines carefully, your package directory should now look like this:

> - files
>   - lib
>     - page
>       - TestPage.class.php
> - templates
>   - test.tpl
> - package.xml
> - page.xml

Both files and templates are archive-based package components, that deploy their payload using tar archives rather than adding the raw files to the package file. Please create the archive `files.tar` and add the contents of the `files/*` directory, but not the directory `files/` itself. Repeat the same process for the `templates` directory, but this time with the file name `templates.tar`. Place both files in the root of your project.

Last but not least, create the package archive `com.example.test.tar` and add all the files listed below.

- `files.tar`
- `package.xml`
- `page.xml`
- `templates.tar`

The archive's filename can be anything you want, all though it is the general convention to use the package name itself for easier recognition.

## Installation

Open the Administration Control Panel and navigate to `Configuration > Packages > Install Package`, click on `Upload Package` and select the file `com.example.test.tar` from your disk. Follow the on-screen instructions until it has been successfully installed.

Open a new browser tab and navigate to your newly created page. If WoltLab Suite is installed at `https://example.com/wsc/`, then the URL should read `https://example.com/wsc/index.php?test/`.

Congratulations, you have just created your first package!

## Appendix

### Template Guessing {#appendixTemplateGuessing}

The class name including the namespace is used to automatically determine the path to the template and its name. The example above used the page class name `wcf\page\TestPage` that is then split into four distinct parts:

1. `wcf`, the internal abbreviation of WoltLab Suite Core (previously known as WoltLab Community Framework)
2. `\page\` (ignored)
3. `Test`, the actual name that is used for both the template and the URL
4. `Page` (page type, ignored)

The fragments `1.` and `4.` from above are used to construct the path to the template: `<installDirOfWSC>/templates/test.tpl` (the first letter of `Test` is being converted to lower-case).

{% include links.html %}
