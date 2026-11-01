# Creating a simple package

## Setup and Requirements

This guide will help you to create a simple package that provides a simple test
page. It is nothing too fancy, but you can use it as the foundation for your
next project.

There are some requirements you should met before starting:

- Text editor with syntax highlighting for PHP, [Notepad++](https://notepad-plus-plus.org/) or [Visual Studio Code](https://code.visualstudio.com/) are a solid pick
 - `*.php` and `*.tpl` should be encoded with ANSI/ASCII
 - `*.xml` are always encoded with UTF-8, but omit the BOM (byte-order-mark)
- An active installation of WoltLab Suite
- An application to create `*.tar` archives, e.g. [7-Zip](http://www.7-zip.org/) on Windows

## The package.xml File

We want to create a simple page that will display the sentence "Hello World" embedded
into the application frame. Create an empty directory in the workspace of your choice
to start with.

Create a new file called `package.xml` and insert the code below:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<package xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/2019/package.xsd" name="com.example.test">
	<packageinformation>
		<!-- com.example.test -->
		<packagename>Simple Package</packagename>
		<packagedescription>A simple package to demonstrate the package system of WoltLab Suite Core</packagedescription>
		<version>1.0.0</version>
		<date>2023-09-15</date>
	</packageinformation>
	<authorinformation>
		<author>Your Name</author>
		<authorurl>http://www.example.com</authorurl>
	</authorinformation>
	<requiredpackages>
		<requiredpackage minversion="6.0.0">com.woltlab.wcf</requiredpackage>
	</requiredpackages>
	<excludedpackages>
		<excludedpackage version="7.0.0 Alpha 1">com.woltlab.wcf</excludedpackage>
	</excludedpackages>
	<instructions type="install">
		<instruction type="file" />
		<instruction type="template" />
		<instruction type="page" />
	</instructions>
</package>
```

There is an [entire chapter](package/package-xml.md) on the package system that explains what the code above
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
	/**
	 * @var string
	 */
	protected $greet = '';

	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['greet'])) $this->greet = $_GET['greet'];
	}

	/**
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();

		if (empty($this->greet)) {
			$this->greet = 'World';
		}
	}

	/**
	 * @inheritDoc
	 */
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
namespace and class name, you can [read more about it later](#template-guessing).

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
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/2019/page.xsd">
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

```
├── files
│   └── lib
│       └── page
│           └── TestPage.class.php
├── package.xml
├── page.xml
└── templates
    └── test.tpl
```

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

## Developer Tools

The developer tools provide an interface to synchronize the data of an installed package with a bare repository on the local disk. You can re-import most PIPs at any time and have the changes applied without crafting a manual update. This process simulates a regular package update with a single PIP only, and resets the cache after the import has been completed.

### Registering a Project

Projects require the absolute path to the package directory, that is, the directory where it can find the `package.xml`. It is not required to install an package to register it as a project, but you have to install it in order to work with it. It does not install the package by itself!

There is a special button on the project list that allows for a mass-import of projects based on a search path. Each direct child directory of the provided path will be tested and projects created this way will use the identifier extracted from the `package.xml`.

### Synchronizing

The install instructions in the `package.xml` are ignored when offering the PIP imports, the detection works entirely based on the default filename for each PIP. On top of that, only PIPs that implement the interface `wcf\system\devtools\pip\IIdempotentPackageInstallationPlugin` are valid for import, as it indicates that importing the PIP multiple times will have no side-effects and that the result is deterministic regardless of the number of times it has been imported.

Some built-in PIPs, such as `sql`, do not qualify for this step and remain unavailable at all times. However, you can still craft and perform an actual package update to have these PIPs executed.

Since WoltLab Suite 6.3, the `script` PIP is offered as an unsafe PIP behind the "Show unsafe PIPs" option. Every install and update script in `files/acp/` (and `files_<abbreviation>/acp/` for apps) is a separate target, for WoltLab Suite Core only the update scripts. The script is included from the installation, so sync the `file` PIP first. Running a script is not idempotent, therefore unsafe PIPs are never part of "Sync All" and must be synced individually.

### Command Line

!!! info "The command line interface of the developer tools is available since WoltLab Suite 6.3."

The script `devtools.php` in the directory of WoltLab Suite Core offers the synchronization without the ACP. It is non-interactive and does not require a login, which makes it suitable for scripts and other tools. The developer tools must be enabled, and the script refuses to run as `root`.

```shell
# List the registered projects as their ID, name and path.
php devtools.php projects

# List the targets of all PIPs, or of a single PIP.
php devtools.php targets --project=com.example.foo
php devtools.php targets --project=com.example.foo --pip=database

# Sync all PIPs in the same order as "Sync All", followed by the package version.
php devtools.php sync --project=com.example.foo --all

# Sync all targets or a single target of a PIP.
php devtools.php sync --project=com.example.foo --pip=file
php devtools.php sync --project=com.example.foo --pip=database --target=update_com.example.foo_1.1_step1.php
php devtools.php sync --project=com.example.foo --pip=script --target=files/acp/update_com.example.foo_1.1.php --allow-unsafe
```

The project can be given by its ID, its name, the identifier of its package or its path. A path must contain a `/` or be `.`, so that a directory in the working directory never shadows a package identifier. Running `php /path/to/wsc/devtools.php sync --project=. --pip=file` from inside the repository of a project syncs the files of that project. The installed version of the package must match the version in the `package.xml` in its major and minor version, the same as in the ACP.

Each sync step runs in a separate PHP process, matching the one request per step in the ACP. A PIP that splits its work, such as `database`, is invoked again until it has completed. The script prints one line per step with the PIP, the target and the time it took, and writes errors to the standard error output. It exits with `0` on success, `1` if a step has failed and `2` on invalid arguments. A failed step stops the sync.

The files and caches are written by the PHP process of the command line, not by the PHP process of the web server. Keep this in mind for your setup:

- Files are created with the user running the script. Run it as the same user as the PHP process of the web server, otherwise the web server may no longer be able to update or delete those files.
- OPcache keeps a separate cache for each PHP process, the command line cannot invalidate the cache of the web server. Updated PHP files are only picked up if `opcache.validate_timestamps` is enabled, which is the default. Depending on `opcache.revalidate_freq`, it can take a few seconds until changes become visible.

## Appendix

### Template Guessing

The class name including the namespace is used to automatically determine the path to the template and its name. The example above used the page class name `wcf\page\TestPage` that is then split into four distinct parts:

1. `wcf`, the internal abbreviation of WoltLab Suite Core (previously known as WoltLab Community Framework)
2. `\page\` (ignored)
3. `Test`, the actual name that is used for both the template and the URL
4. `Page` (page type, ignored)

The fragments `1.` and `3.` from above are used to construct the path to the template: `<installDirOfWSC>/templates/test.tpl` (the first letter of `Test` is being converted to lower-case).
