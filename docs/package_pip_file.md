# File Package Installation Plugin

Adds any type of files with the exception of templates.

!!! warning "You cannot overwrite files provided by other packages."

The `application` attribute behaves like it does for [acp templates](package_pip_acp-template.md#application).


## Archive

The `acpTemplate` package installation plugins expects a `.tar` (recommended) or `.tar.gz` archive.
The file path given in the `instruction` element as its value must be relative to the `package.xml` file.


## Example in `package.xml`

```xml
<instruction type="file" />
<!-- is the same as -->
<instruction type="file">files.tar</instruction>

<!-- if an application "com.woltlab.example" is being installed, the following lines are equivalent -->
<instruction type="file" />
<instruction type="file" application="example" />

<!-- if the same application wants to install additional files, in WoltLab Suite Core's directory: -->
<instruction type="file" application="wcf">files_wcf.tar</instruction>
```
