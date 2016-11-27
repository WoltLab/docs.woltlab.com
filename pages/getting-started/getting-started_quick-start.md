---
title: Creating a simple package
sidebar: sidebar
permalink: getting-started_quick-start.html
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
		<date>YYYY-MM-DD</date>
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

This page inherits from [wcf\page\AbstractPage](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/page/AbstractPage.class.php) which provides a lot of useful methods required to display a page. It enables you to write just the code you really need.

Let's take a step back and look at what we've done! We have defined our PHP class along with a member `$world`, which gets the string `'world'` assigned to it. `readData()` is the perfect spot to place code which fetches data from any source and will always be executed by WCF. The next method `assignVariables()` is used to pass our previously defined variable to the template engine. After all we want to display something on our page, don't we?

Now that we have defined the body of our PHP class, we should add the template which actually displays the stuff we want.

But wait, how does WCF know what is the right template to pick? We have never told WCF where it can find the template nor what its name is. This is where the main idea behind WCF kicks in: Don't bother the developer with stupid and boring stuff, ease his/her life. You can read the section on [template guessing](#appendixTemplateGuessing) if you want to know how it works.

By the way: Have you noticed the missing `?>` (closing PHP tag)? This was left out intentionally, preventing PHP from breaking if there is any whitespace. You should never append it.

## The Template

Navigate back to the root directory of your package until you see both the `files` directory and the `package.xml`. Now create a directory called `templates`, open it and create the file `test.tpl`.

```smarty
{include file='header'}

<div class="section">
	Hello {$greet}!
</div>

{include file='footer'}
```

That's it, you now have a fully working page which displays the phrase `Hello World!`. Even though this all might sound a bit of an overkill for outputing such a simple string, but you should be made aware of the possibilities offered by WCF. This is not the usual quick'n'dirty application you might have encountered in the past, it indeed requires some effort to aquire the knowledge, but it is worth the time.

## Building the Package

If you have followed the guidelines above carefully, your package directory should now look like this:

> - files
 - lib
   - page
     - TestPage.class.php
- templates
  - test.tpl
- package.xml

Please open the directory `files` and add the directory (and its contents) to a TAR-archive called `files.tar`, move it to the parent directory (the same where `package.xml` is located). Do not make the mistake of adding the `files` directory itself to the archive, if you open the archive (e.g. with 7-Zip) it should only display the folder `lib`.

Now switch to the `templates` directory and add all `*.tpl` files to a TAR-archive called `templates.tar`, again move it to the parent directory.

We're almost there: Create another TAR-archive called `com.example.package.tar` and add these files to it:

- `files.tar`
- `package.xml`
- `templates.tar`

## Installation

Open the Administration Control Panel and navigate to `System > Packages > Install Package`. Click on `Upload Package` and select the file `com.example.package.tar`. Follow the on-screen instructions until it has been successfully installed.

Now use your browser and navigate to the primary application. If it is installed on `http://localhost/wbb/` simply go to `http://localhost/wbb/index.php/Test/`. It should display a fancy page with the string `Hello World!` in the center.

Congratulations, you have just created your first package!

## Appendix

### Template Guessing

 WCF examines the namespace of our class and the name of our class, combining both gives WCF everything it needs to load the right template. In our example we have defined the class namespace to be `wcf\page`, WCF now picks the string before the first `\` and uses it to determine the application.

The next step is to examine the class name, since our page is called `TestPage`, WCF strips the `Page` part and converts the first char of the result to lowercase. WCF now assumes the template is called `test` and can be found here: `wcf/templates/test.tpl`. You should be aware, that only the first char is actually converted, if you class is called `MyAwesomePage`, the template name will be `myAwesome`.
