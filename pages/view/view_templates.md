---
title: Templates
sidebar: sidebar
permalink: view_templates.html
folder: view
parent: view
---

Templates are responsible for the output a user sees when requesting a page (while the PHP code is responsible for providing the data that will be shown).
Templates are text files with `.tpl` as the file extension.
WoltLab Suite Core compiles the template files once into a PHP file that is executed when a user requests the page.
In subsequent request, as the PHP file containing the compiled template already exists, compiling the template is not necessary anymore.


## Template Types and Conventions

WoltLab Suite Core supports two types of templates:
frontend templates (or simply *templates*) and backend templates (*ACP templates*).
Each type of template is only available in its respective domain, thus frontend templates cannot be included or used in the ACP and vice versa.

For pages and forms, the name of the template matches the unqualified name of the PHP class except for the `Page` or `Form` suffix:

- `RegisterForm.class.php` → `register.tpl`
- `UserPage.class.php` → `user.tpl`

If you follow this convention, WoltLab Suite Core will automatically determine the template name so that you do not have to explicitly set it.

{% include callout.html content="For forms that handle creating and editing objects, in general, there are two form classes: `FooAddForm` and `FooEditForm`. WoltLab Suite Core, however, generally only uses one template `fooAdd.tpl` and the template variable `$action` to distinguish between creating a new object (`$action = 'add'`) and editing an existing object (`$action = 'edit'`) as the differences between templates for adding and editing an object are minimal." type="info" %}



## Installing Templates

Templates and ACP templates are installed by two different package installation plugins:
the [template PIP](package_pip_template.html) and the [ACP template PIP](package_pip_acp-template.html).
More information about installing templates can be found on those pages. 


## Base Templates

### Frontend

```smarty
{include file='header'}

{* content *}

{include file='footer'}
```

### Backend

```smarty
{include file='header' pageTitle='foo.bar.baz'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">Title</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			{* your default content header navigation buttons *}
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{* content *}

{include file='footer'}
```

`foo.bar.baz` is the language item that contains the title of the page.


## Common Template Components

### Forms

```smarty
<form method="post" action="{link controller='FooBar'}{/link}">
	<div class="section">
		<dl{if $errorField == 'baz'} class="formError"{/if}>
			<dt><label for="baz">{lang}foo.bar.baz{/lang}</label></dt>
			<dd>
				<input type="text" id="baz" name="baz" value="{$baz}" class="long" required autofocus>
				{if $errorField == 'baz'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}foo.bar.baz.error.{@$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>
		
		<dl>
			<dt><label for="bar">{lang}foo.bar.bar{/lang}</label></dt>
			<dd>
				<textarea name="bar" id="bar" cols="40" rows="10">{$bar}</textarea>
				{if $errorField == 'bar'}
					<small class="innerError">{lang}foo.bar.bar.error.{@$errorType}{/lang}</small>
				{/if}
			</dd>
		</dl>
		
		{* other fields *}
		
		{event name='dataFields'}
	</div>
	
	{* other sections *}
	
	{event name='sections'}
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>
```

### Tab Menus 

```smarty
<div class="section tabMenuContainer">
	<nav class="tabMenu">
		<ul>
			<li><a href="{@$__wcf->getAnchor('tab1')}">Tab 1</a></li>
			<li><a href="{@$__wcf->getAnchor('tab2')}">Tab 2</a></li>
			
			{event name='tabMenuTabs'}
		</ul>
	</nav>
	
	<div id="tab1" class="tabMenuContent">
		<div class="section">
			{* contents of first tab *}
		</div>
	</div>
	
	<div id="tab2" class="tabMenuContainer tabMenuContent">
		<nav class="menu">
			<ul>
				<li><a href="{@$__wcf->getAnchor('tab2A')}">Tab 2A</a></li>
				<li><a href="{@$__wcf->getAnchor('tab2B')}">Tab 2B</a></li>
				
				{event name='tabMenuTab2Subtabs'}
			</ul>
		</nav>
		
		<div id="tab2A" class="tabMenuContent">
			<div class="section">
				{* contents of first subtab for second tab *}
			</div>
		</div>
		
		<div id="tab2B" class="tabMenuContent">
			<div class="section">
				{* contents of second subtab for second tab *}
			</div>
		</div>
		
		{event name='tabMenuTab2Contents'}
	</div>
	
	{event name='tabMenuContents'}
</div>
```


## Template Scripting

### Template Variables

Template variables can be assigned via `WCF::getTPL()->assign('foo', 'bar')` and accessed in templates via `$foo`:

- `{$foo}` will result in the contents of `$foo` to be passed to `StringUtil::encodeHTML()` before being printed.
- `{#$foo}` will result in the contents of `$foo` to be passed to `StringUtil::formatNumeric()` before being printed.
  Thus, this method is relevant when printing numbers and having them formatted correctly according the the user’s language.
- `{@$foo}` will result in the contents of `$foo` to be printed directly.
  In general, this method should not be used for user-generated input.

Multiple template variables can be assigned by passing an array:

```php
WCF::getTPL()->assign([
	'foo' => 'bar',
	'baz' => false 
]);
```

#### Modifiers

If you want to call a function on a variable, you can use the modifier syntax:
`{@$foo|trim}`, for example, results in the trimmed contents of `$foo` to be printed.

#### System Template Variable

The template variable `$tpl` is automatically assigned and is an array containing different data:

- `$tpl[get]` contains `$_GET`.
- `$tpl[post]` contains `$_POST`.
- `$tpl[cookie]` contains `$_COOKIE`.
- `$tpl[server]` contains `$_SERVER`.
- `$tpl[env]` contains `$_ENV`.
- `$tpl[now]` contains `TIME_NOW` (current timestamp).

Furthermore, the following template variables are also automatically assigned:

- `$__wcf` contains the `WCF` object (or `WCFACP` object in the backend).

### Comments

Comments are wrapped in `{*` and `*}` and can span multiple lines:

```smarty
{* some
   comment *}
```

{% include callout.html content="The template compiler discards the comments, so that they not included in the compiled template." type="info" %}

### Conditions

Conditions follow a similar syntax to PHP code:

```smarty
{if $foo === 'bar'}
	foo is bar
{elseif $foo === 'baz'}
	foo is baz
{else}
	foo is neither bar nor baz
{/if}
```

The supported operators in conditions are `===`, `!==`, `==`, `!=`, `<=`, `<`, `>=`, `>`, `||`, `&&`, `!`, and `=`.

More examples:

````smarty
{if $bar|isset}…{/if}

{if $bar|count > 3 && $bar|count < 100}…{/if}
````

### Foreach Loops

Foreach loops allow to iterate over arrays or iterable objects:

```smarty
<ul>
	{foreach from=$array key=key item=value}
		<li>{$key}: {$value}</li>
	{/foreach}
</ul>
```

While the `from` attribute containing the iterated structure and the `item` attribute containg the current value are mandatory, the `key` attribute is optional.
If the foreach loop has a name assigned to it via the `name` attribute, the `$tpl` template variable provides additional data about the loop:

```smarty
<ul>
	{foreach from=$array key=key item=value name=foo}
		{if $tpl[foreach][foo][first]}
			something special for the first iteration
		{elseif $tpl[foreach][foo][last]}
			something special for the last iteration
		{/if}
		
		<li>iteration {#$tpl[foreach][foo][iteration]+1} out of {#$tpl[foreach][foo][total]} {$key}: {$value}</li>
	{/foreach}
</ul>
```

In contrast to PHP’s foreach loop, templates also support `foreachelse`:

```smarty
{foreach from=$array item=value}
	…
{foreachelse}
	there is nothing to iterate over
{/foreach}
```

### Including Other Templates

To include template named `foo` from the same domain (frontend/backend), you can use

```smarty
{include file='foo'}
```

If the template belongs to an application, you have to specify that application using the `application` attribute:

```smarty
{include file='foo' application='app'}
```

Additional template variables can be passed to the included template as additional attributes:

```smarty
{include file='foo' application='app' var1='foo1' var2='foo2'}
```

### Template Plugins

An overview of all available template plugins can be found [here](view_template-plugins.html).
