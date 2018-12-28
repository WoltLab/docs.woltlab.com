---
title: Template Plugins
sidebar: sidebar
permalink: view_template-plugins.html
folder: view
parent: view_templates
---


## `append`

If a string should be appended to the value of a variable, `append` can be used:

```smarty
{assign var=templateVariable value='newValue'}

{$templateVariable} {* prints 'newValue *}

{append var=templateVariable value='2'}

{$templateVariable} {* now prints 'newValue2 *}
```

If the variables does not exist yet, `append` creates a new one with the given value.
If `append` is used on an array as the variable, the value is appended to all elements of the array.


## `assign`

New template variables can be declared and new values can be assigned to existing template variables using `assign`:

```smarty
{assign var=templateVariable value='newValue'}

{$templateVariable} {* prints 'newValue *}
```


## `capture`

In some situations, `assign` is not sufficient to assign values to variables in templates if the value is complex.
Instead, `capture` can be used:

```smarty
{capture var=templateVariable}
	{if $foo}
		<p>{$bar}</p>
	{else}
		<small>{$baz}</small>
	{/if}
{/capture}
```


## `concat`

`concat` is a modifier used to concatenate multiple strings:

```smarty
{assign var=foo value='foo'}

{assign var=templateVariable value='bar'|concat:$foo}

{$templateVariable} {* prints 'foobar *}
```


## `counter`

`counter` can be used to generate and optionally print a counter:

```smarty
{counter name=fooCounter print=true} {* prints '1' *}

{counter name=fooCounter print=true} {* prints '2' now *}

{counter name=fooCounter} {* prints nothing, but counter value is '3' now internally *}

{counter name=fooCounter print=true} {* prints '4' *}
```

Counter supports the following attributes:

| Attribute | Description |
|-----------|-------------|
| `assign` | optional name of the template variable the current counter value is assigned to |
| `direction` | counting direction, either `up` or `down`; `up` by default |
| `name` | name of the counter, relevant if multiple counters are used simultaneously |
| `print` | if `true`, the current counter value is printed; `false` by default |
| `skip` | positive counting increment; `1` by default |
| `start` | start counter value; `1` by default |


## `currency`

`currency` is a modifier used to format currency values with two decimals using language dependent thousands separators and decimal point:

```smarty
{assign var=currencyValue value=12.345}

{$currencyValue|currency} {* prints '12.34' *}
```


## `cycle`

`cycle` can be used to cycle between different values:

```smarty
{cycle name=fooCycle values='bar,baz'} {* prints 'bar' *}

{cycle name=fooCycle} {* prints 'baz' *}

{cycle name=fooCycle advance=false} {* prints 'baz' again *}

{cycle name=fooCycle} {* prints 'bar' *}
```

{% include callout.html content="The values attribute only has to be present for the first call. If `cycle` is used in a loop, the presence of the same values in consecutive calls has no effect. Only once the values change, the cycle is reset." type="info" %}

| Attribute | Description |
|-----------|-------------|
| `advance` | if `true`, the current cycle value is advanced to the next value; `true` by default |
| `assign` | optional name of the template variable the current cycle value is assigned to; if used, `print` is set to `false` | 
| `delimiter` | delimiter between the different cycle values; `,` by default |
| `name` | name of the cycle, relevant if multiple cycles are used simultaneously |
| `print` | if `true`, the current cycle value is printed, `false` by default |
| `reset` | if `true`, the current cycle value is set to the first value, `false` by default |
| `values` | string containing the different cycles values, also see `delimiter` |


## `date`

`date` generated a formatted date using `wcf\util\DateUtil::format()` with `DateUtil::DATE_FORMAT` internally.

```smarty
{$timestamp|date}
```


## <span class="label label-info">3.1+</span> `dateInterval`

`dateInterval` calculates the difference between two unix timestamps and generated a textual date interval.

```smarty
{dateInterval start=$startTimestamp end=$endTimestamp full=true format='sentence'}
```

| Attribute | Description |
|-----------|-------------|
| `end` | end of the time interval; current timestamp by default (though either `start` or `end` has to be set) |
| `format` | output format, either `default`, `sentence`, or `plain`; defaults to `default`, see `wcf\util\DateUtil::FORMAT_*` constants |
| `full` | if `true`, full difference in minutes is shown; if `false`, only the longest time interval is shown; `false` by default |
| `start` | start of the time interval; current timestamp by default (though either `start` or `end` has to be set) |


## `encodeJS`

`encodeJS` encodes a string to be used as a single-quoted string in JavaScript by replacing `\\` with `\\\\`, `'` with `\'`, linbreaks with `\n`, and `/` with `\/`.

```smarty
<script>
	var foo = '{@$foo|encodeJS}';
</script>
```


## `encodeJSON`

`encodeJS` encodes a JSON string to be used as a single-quoted string in JavaScript by replacing `\\` with `\\\\`, `'` with `&#39;`, linbreaks with `\n`, and `/` with `\/`.
Additionally, `htmlspecialchars` is applied to the string.

```smarty
'{@$foo|encodeJSON}'
```


## `escapeCDATA`

`escapeCDATA` encodes a string to be used in a `CDATA` element by replacing `]]>` with `]]]]><![CDATA[>`.

```smarty
<![CDATA[{@$foo|encodeCDATA}]]>
```


## `event`

`event` provides extension points in templates that [template listeners](package_pip_template-listener.html) can use.

```smarty
{event name='foo'}
```


## `fetch`

`fetch` fetches the contents of a file using `file_get_contents`.

```smarty
{fetch file='foo.html'} {* prints the contents of `foo.html` *}

{fetch file='bar.html' assign=bar} {* assigns the contents of `foo.html` to `$bar`; does not print the contents *}
```


## `filesizeBinary`

`filesizeBinary` formats the filesize using binary filesize (in bytes).

```smarty
{$filesize|filesizeBinary}
```


## `filesize`

`filesize` formats the filesize using filesize (in bytes).

```smarty
{$filesize|filesize}
```


## `hascontent`

In many cases, conditional statements can be used to determine if a certain section of a template is shown:

```smarty
{if $foo === 'bar'}
	only shown if $foo is bar
{/if}
```

In some situations, however, such conditional statements are not sufficient.
One prominent example is a template event:

```smarty
{if $foo === 'bar'}
	<ul>
		{if $foo === 'bar'}
			<li>Bar</li>
		{/if}
		
		{event name='listItems'}
	</li>
{/if}
```

In this example, if `$foo !== 'bar'`, the list will not be shown, regardless of the additional template code provided by template listeners.
In such a situation, `hascontent` has to be used:

```smarty
{hascontent}
	<ul>
		{content}
			{if $foo === 'bar'}
				<li>Bar</li>
			{/if}
			
			{event name='listItems'}
		{/content}
	</ul>
{/hascontent}
```

If the part of the template wrapped in the `content` tags has any (trimmed) content, the part of the template wrapped by `hascontent` tags is shown (including the part wrapped by the `content` tags), otherwise nothing is shown.
Thus, this construct avoids an empty list compared to the `if` solution above.

Like `foreach`, `hascontent` also supports an `else` part:

```smarty
{hascontent}
	<ul>
		{content}
			{* … *}
		{/content}
	</ul>
{hascontentelse}
	no list
{/hascontent}
```


## `htmlCheckboxes`

`htmlCheckboxes` generates a list of HTML checkboxes.

```smarty
{htmlCheckboxes name=foo options=$fooOptions selected=$currentFoo}

{htmlCheckboxes name=bar output=$barLabels values=$barValues selected=$currentBar}
```

| Attribute | Description |
|-----------|-------------|
| `disableEncoding` | if `true`, the values are not passed through `wcf\util\StringUtil::encodeHTML()`; `false` by default |
| `name` | `name` attribute of the `input` checkbox element |
| `output` | array used as keys and values for `options` if present; not present by default |
| `options` | array selectable options with the key used as `value` attribute and the value as the checkbox label |
| `selected` | current selected value(s) |
| `separator` | separator between the different checkboxes in the generated output; empty string by default |
| `values` | array with values used in combination with `output`, where `output` is only used as keys for `options` |


## `htmlOptions`

`htmlOptions` generates an `select` HTML element.

```smarty
{htmlOptions name='foo' options=$options selected=$selected}

<select name="bar">
	<option value=""{if !$selected} selected{/if}>{lang}foo.bar.default{/lang}</option>
	{htmlOptions options=$options selected=$selected} {* no `name` attribute *}
</select>
```

| Attribute | Description |
|-----------|-------------|
| `disableEncoding` | if `true`, the values are not passed through `wcf\util\StringUtil::encodeHTML()`; `false` by default |
| `object` | optional instance of `wcf\data\DatabaseObjectList` that provides the selectable options (overwrites `options` attribute internally) |
| `name` | `name` attribute of the `select` element; if not present, only the <strong>contents</strong> of the `select` element are printed |
| `output` | array used as keys and values for `options` if present; not present by default |
| `values` | array with values used in combination with `output`, where `output` is only used as keys for `options` |
| `options` | array selectable options with the key used as `value` attribute and the value as the option label; if a value is an array, an `optgroup` is generated with the array key as the `optgroup` label |
| `selected` | current selected value(s) |

All additional attributes are added as attributes of the `select` HTML element.


## `implode`

`implodes` transforms an array into a string and prints it.

```smarty
{implode from=$array key=key item=item glue=";"}{$key}: {$value}{/implode}
```

| Attribute | Description |
|-----------|-------------|
| `from` | array with the imploded values |
| `glue` | separator between the different array values; `', '` by default |
| `item` | template variable name where the current array value is stored during the iteration |
| `key` | optional template variable name where the current array key is stored during the iteration |


## <span class="label label-info">3.0+</span> `js`

`js` generates script tags based on whether `ENABLE_DEBUG_MODE` and `VISITOR_USE_TINY_BUILD` are enabled.

```smarty
{js application='wbb' file='WBB'} {* generates 'http://example.com/js/WBB.js' *}

{js application='wcf' file='WCF.Like' bundle='WCF.Combined'}
	{* generates 'http://example.com/wcf/js/WCF.Like.js' if ENABLE_DEBUG_MODE=1 *}
	{* generates 'http://example.com/wcf/js/WCF.Combined.min.js' if ENABLE_DEBUG_MODE=0 *}

{js application='wcf' lib='jquery'}
	{* generates 'http://example.com/wcf/js/3rdParty/jquery.js' *}

{js application='wcf' lib='jquery-ui' file='awesomeWidget'}
	{* generates 'http://example.com/wcf/js/3rdParty/jquery-ui/awesomeWidget.js' *}

{js application='wcf' file='WCF.Like' bundle='WCF.Combined' hasTiny=true}
	{* generates 'http://example.com/wcf/js/WCF.Like.js' if ENABLE_DEBUG_MODE=1 *}
	{* generates 'http://example.com/wcf/js/WCF.Combined.min.js' (ENABLE_DEBUG_MODE=0 *}
	{* generates 'http://example.com/wcf/js/WCF.Combined.tiny.min.js' if ENABLE_DEBUG_MODE=0 and VISITOR_USE_TINY_BUILD=1 *}
```


## `lang`

`lang` replaces a language items with its value.

```smarty
{lang}foo.bar.baz{/lang}

{lang __literal=true}foo.bar.baz{/lang}

{lang foo='baz'}foo.bar.baz{/lang}

{lang}foo.bar.baz.{$action}{/lang}
```

| Attribute | Description |
|-----------|-------------|
| `__encode` | if `true`, the output will be passed through `StringUtil::encodeHTML()` |
| `__literal` | if `true`, template variables will not resolved but printed as they are in the language item; `false` by default |
| `__optional` | if `true` and the language item does not exist, an empty string is printed; `false` by default |

All additional attributes are available when parsing the language item.


## `language`

`language` replaces a language items with its value.
If the template variable `__language` exists, this language object will be used instead of `WCF::getLanguage()`.
This modifier is useful when assigning the value directly to a variable.

```smarty
{$languageItem|language}

{assign var=foo value=$languageItem|language}
```


## `link`

`link` generates internal links using `LinkHandler`.

```smarty
<a href="{link controller='FooList' application='bar'}param1=2&param2=A{/link}">Foo</a>
```

| Attribute | Description |
|-----------|-------------|
| `application` | abbreviation of the application the controller belongs to; `wcf` by default |
| `controller` | name of the controller; if not present, the landing page is linked in the frontend and the index page in the ACP |
| `encode` | if `true`, the generated link is passed through `wcf\util\StringUtil::encodeHTML()`; `true` by default |
| `isEmail` | sets `encode=false` and forces links to link to the frontend |

Additional attributes are passed to `LinkHandler::getLink()`.


## `newlineToBreak`

`newlineToBreak` transforms newlines into HTML `<br>` elements after encoding the content via `wcf\util\StringUtil::encodeHTML()`.

```smarty
{$foo|newlineToBreak}
```


## <span class="label label-info">3.0+</span> `page`

`page` generates an internal link to a CMS page.

```smarty
{page}com.woltlab.wcf.CookiePolicy{/page}

{page pageID=1}{/page}

{page language='de'}com.woltlab.wcf.CookiePolicy{/page}

{page languageID=2}com.woltlab.wcf.CookiePolicy{/page}
```

| Attribute | Description |
|-----------|-------------|
| `pageID` | unique id of the page (cannot be used together with a page identifier as value)  |
| `languageID` | id of the page language (cannot be used together with `language`) |
| `language` | language code of the page language (cannot be used together with `languageID`) |


## `pages`

`pages` generates a pagination.

```smarty
{pages controller='FooList' link="pageNo=%d" print=true assign=pagesLinks} {* prints pagination *}

{@$pagesLinks} {* prints same pagination again *}
```

| Attribute | Description |
|-----------|-------------|
| `assign` | optional name of the template variable the pagination is assigned to |
| `controller` | controller name of the generated links |
| `link` | additional link parameter where `%d` will be replaced with the relevant page number |
| `pages` | maximum number of of pages; by default, the template variable `$pages` is used |
| `print` | if `false` and `assign=true`, the pagination is not printed |
| `application`, `id`, `object`, `title` | additional parameters passed to `LinkHandler::getLink()` to generate page links |


## `plainTime`

`plainTime` formats a timestamp to include year, month, day, hour, and minutes.
The exact formatting depends on the current language (via the language items `wcf.date.dateTimeFormat`, `wcf.date.dateFormat`, and `wcf.date.timeFormat`).

```smarty
{$timestamp|plainTime}
```


## `prepend`

If a string should be prepended to the value of a variable, `prepend` can be used:

```smarty
{assign var=templateVariable value='newValue'}

{$templateVariable} {* prints 'newValue *}

{prepend var=templateVariable value='2'}

{$templateVariable} {* now prints '2newValue' *}
```

If the variables does not exist yet, `prepend` creates a new one with the given value.
If `prepend` is used on an array as the variable, the value is prepended to all elements of the array.


## `shortUnit`

`shortUnit` shortens numbers larger than 1000 by using unit suffixes:

```smarty
{10000|shortUnit} {* prints 10k *}
{5400000|shortUnit} {* prints 5.4M *}
```


## `smallpages`

`smallpages` generates a smaller version of `pages` by using adding the `small` CSS class to the generated `<nav>` element and only showing 7 instead of 9 links. 


## `tableWordwrap`

`tableWordwrap` inserts zero width spaces every 30 characters in words longer than 30 characters.

```smarty
{$foo|tableWordwrap}
```


## `time`

`time` generates an HTML `time` elements based on a timestamp that shows a relative time or the absolute time if the timestamp more than six days ago.

```smarty
{$timestamp|time} {* prints a '<time>' element *}
```


## `truncate`

`truncate` truncates a long string into a shorter one:

```smarty
{$foo|truncate:35}

{$foo|truncate:35:'_':true}
```


| Parameter Number | Description |
|-----------|-------------|
| 0 | truncated string |
| 1 | truncated length; `80` by default |
| 2 | ellipsis symbol; `wcf\util\StringUtil::HELLIP` by default |
| 3 | if `true`, words can be broken up in the middle; `false` by default |
