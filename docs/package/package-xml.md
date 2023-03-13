# package.xml

The `package.xml` is the core component of every package.
It provides the meta data (e.g. package name, description, author) and the instruction set for a new installation and/or updating from a previous version.

## Example

{jinja{ codebox(
  title="package.xml",
  language="xml",
  filepath="package/package.xml"
) }}


## Elements

### `<package>`

The root node of every `package.xml` it contains the reference to the namespace and the location of the XML Schema Definition (XSD).

The attribute `name` is the most important part, it holds the unique package identifier and is mandatory.
It is based upon your domain name and the package name of your choice.

For example WoltLab Suite Forum (formerly know an WoltLab Burning Board and usually abbreviated as `wbb`) is created by WoltLab which owns the domain `woltlab.com`.
The resulting package identifier is `com.woltlab.wbb` (`<tld>.<domain>.<packageName>`).

### `<packageinformation>`

Holds the entire meta data of the package.

#### `<packagename>`

This is the actual package name displayed to the end user, this can be anything you want, try to keep it short.
It supports the attribute `language` which allows you to provide the package name in different languages, please be aware that if it is not present, `en` (English) is assumed:

```xml
<packageinformation>
	<packagename>Simple Package</packagename>
	<packagename language="de">Einfaches Paket</packagename>
</packageinformation>
```

#### `<packagedescription>`

Brief summary of the package, use it to explain what it does since the package name might not always be clear enough.
The attribute `language` can also be used here, please reference to [`<packagename>`](#packageName) for details.

#### `<version>`

The package's version number, this is a string consisting of three numbers separated with a dot and optionally followed by a keyword (must be followed with another number).

The possible keywords are:

- Alpha/dev (both is regarded to be the same)
- Beta
- RC (release candidate)

Valid examples:

- 1.0.0
- 1.12.13 Alpha 19

Invalid examples:

- 1.0.0 Beta (keyword Beta must be followed by a number)
- 2.0 RC 3 (version number must consist of 3 blocks of numbers)
- 1.2.3 dev 4.5 (4.5 is not an integer, 4 or 5 would be valid but not the fraction)

#### `<date>`

Must be a valid [ISO 8601](http://en.wikipedia.org/wiki/ISO_8601) date, e.g. `2013-12-27`.

#### `<packageurl>`

> (optional)

URL to the package website that provides detailed information about the package.

### `<authorinformation>`

Holds meta data regarding the package's author.

#### `<author>`

Can be anything you want.

#### `<authorurl>`

> (optional)

URL to the author's website.

### `<requiredpackages>`

A list of packages including their version required for this package to work.

#### `<requiredpackage>`

Example:

```xml
<requiredpackage minversion="2.7.5" file="requirements/com.example.foo.tar">com.example.foo</requiredpackage>
```

The attribute `minversion` must be a valid version number as described in [`<version>`](#version).
The `file` attribute is optional and specifies the location of the required package's archive relative to the `package.xml`.

### `<optionalpackage>`

A list of optional packages which can be selected by the user at the very end of the installation process.

#### `<optionalpackage>`

Example:

```xml
<optionalpackage file="optionals/com.example.bar.tar">com.example.bar</optionalpackage>
```

The `file` attribute specifies the location of the optional package's archive relative to the `package.xml`.

### `<excludedpackages>`

List of packages which conflict with this package. It is not possible to install it if any of the specified packages is installed. In return you cannot install an excluded package if this package is installed.

#### `<excludedpackage>`

Example:

```xml
<excludedpackage version="7.0.0 Alpha 1">com.woltlab.wcf</excludedpackage>
```

The attribute `version` must be a valid version number as described in the [\<version\>](#version) section. In the example above it will be impossible to install this package in WoltLab Suite Core 7.0.0 Alpha 1 or higher.


### `<instructions>`

List of instructions to be executed upon install or update. The order is important, the topmost `<instruction>` will be executed first.

#### `<instructions type="install">`

List of instructions for a new installation of this package.

#### `<instructions type="update" fromversion="…">`

The attribute `fromversion` must be a valid version number as described in the [\<version\>](#version) section and specifies a possible update from that very version to the package's version.

!!! warning "The installation process will pick exactly one update instruction, ignoring everything else. Please read the explanation below!"

Example:

- Installed version: `1.0.0`
- Package version: `1.0.2`

```xml
<instructions type="update" fromversion="1.0.0">
	<!-- … -->
</instructions>
<instructions type="update" fromversion="1.0.1">
	<!-- … -->
</instructions>
```

In this example WoltLab Suite Core will pick the first update block since it allows an update from `1.0.0 -> 1.0.2`.
The other block is not considered, since the currently installed version is `1.0.0`. After applying the update block (`fromversion="1.0.0"`), the version now reads `1.0.2`.

#### `<instruction>`

Example:

```xml
<instruction type="objectTypeDefinition">objectTypeDefinition.xml</instruction>
```

The attribute `type` specifies the instruction type which is used to determine the package installation plugin (PIP) invoked to handle its value.
The value must be a valid file relative to the location of `package.xml`.
Many PIPs provide default file names which are used if no value is given:

```xml
<instruction type="objectTypeDefinition" />
```

There is a [list of all default PIPs](pip.md) available.

!!! warning "Both the `type`-attribute and the element value are case-sensitive. Windows does not care if the file is called `objecttypedefinition.xml` but was referenced as `objectTypeDefinition.xml`, but both Linux and Mac systems will be unable to find the file."

In addition to the `type` attribute, an optional `run` attribute (with `standalone` as the only valid value) is supported which forces the installation to execute this PIP in an isolated request, allowing a single, resource-heavy PIP to execute without encountering restrictions such as PHP’s `memory_limit` or `max_execution_time`:

```xml
<instruction type="file" run="standalone" />
```

#### `<void/>`

Sometimes a package update should only adjust the metadata of the package, for example, an optional package was added.
However, WoltLab Suite Core requires that the list of `<instructions>` is non-empty.
Instead of using a dummy `<instruction>` that idempotently updates some PIP, the `<void/>` tag can be used for this use-case.

Using the `<void/>` tag is only valid for `<instructions type="update">` and must not be accompanied by other `<instruction>` tags.

Example:

```xml
<instructions type="update" fromversion="1.0.0">
	<void/>
</instructions>
```
