# Template Modifiers

Variable modifiers are used to modify the output of variables within templates.
These modifiers allow you to perform various operations on the variables before displaying them.
The general syntax for applying a variable modifier is `{$variable|modifier}`.

Modifiers can be chained together to perform multiple operations on a variable. In such cases, the modifiers are applied from left to right. For example:
```smarty
{$variable|modifier1|modifier2|modifier3}
```

A modifier may accept additional parameters that affect its behavior. These parameters follow the modifier name and are separated by a `:`. For example:
```smarty
{$variable|modifier:'param1':'param2'}
```

## Build-in Modifiers

### `|concat`

`concat` is a modifier used to concatenate multiple strings:

```smarty
{assign var=foo value='foo'}

{assign var=templateVariable value='bar'|concat:$foo}

{$templateVariable} {* prints 'foobar *}
```

### `|currency`

`currency` is a modifier used to format currency values with two decimals using language dependent thousands separators and decimal point:

```smarty
{assign var=currencyValue value=12.345}

{$currencyValue|currency} {* prints '12.34' *}
```

### `|date`

!!! info "This template plugin has been deprecated in WoltLab Suite 6.0. Use `{time type='plainDate'}` or `{time type='custom'}` instead."

`date` generated a formatted date using `wcf\util\DateUtil::format()` with `DateUtil::DATE_FORMAT` internally.

```smarty
{$timestamp|date}
```

### `|encodeJS`

`encodeJS` encodes a string to be used as a single-quoted string in JavaScript by replacing `\\` with `\\\\`, `'` with `\'`, linebreaks with `\n`, and `/` with `\/`.

```smarty
<script>
	var foo = '{unsafe:$foo|encodeJS}';
</script>
```

### `|escapeCDATA`

`escapeCDATA` encodes a string to be used in a `CDATA` element by replacing `]]>` with `]]]]><![CDATA[>`.

```smarty
<![CDATA[{unsafe:$foo|encodeCDATA}]]>
```


### `|filesizeBinary`

`filesizeBinary` formats the filesize using binary filesize (in bytes).

```smarty
{$filesize|filesizeBinary}
```


### `|filesize`

`filesize` formats the filesize using filesize (in bytes).

```smarty
{$filesize|filesize}
```

### `|ipSearch`

`ipSearch` generates a link to search for an IP address.

```smarty
{"127.0.0.1"|ipSearch}
```

### `|json`

`json` JSON-encodes the given value.

```smarty
<script>
let data = { "title": {unsafe:$foo->getTitle()|json} };
</script>
```

### `|language`

`language` replaces a language items with its value.
If the template variable `__language` exists, this language object will be used instead of `WCF::getLanguage()`.
This modifier is useful when assigning the value directly to a variable.

Note that template scripting is applied to the output of the variable, which can lead to unwanted side effects. Use `phrase` instead if you don't want to use template scripting.

```smarty
{$languageItem|language}

{assign var=foo value=$languageItem|language}
```

### `|newlineToBreak`

`newlineToBreak` transforms newlines into HTML `<br>` elements after encoding the content via `wcf\util\StringUtil::encodeHTML()`.

```smarty
{$foo|newlineToBreak}
```

### `|phrase`

`phrase` replaces a language items with its value.
If the template variable `__language` exists, this language object will be used instead of `WCF::getLanguage()`.
This modifier is useful when assigning the value directly to a variable.

`phrase` should be used instead of `language` unless you want to explicitly allow template scripting on a variable's output.

```smarty
{$languageItem|phrase}

{assign var=foo value=$languageItem|phrase}
```

### `|plainTime`

!!! info "This template plugin has been deprecated in WoltLab Suite 6.0. Use `{time type='plainTime'}` instead."

`plainTime` formats a timestamp to include year, month, day, hour, and minutes.
The exact formatting depends on the current language (via the language items `wcf.date.dateTimeFormat`, `wcf.date.dateFormat`, and `wcf.date.timeFormat`).

```smarty
{$timestamp|plainTime}
```

### `|shortUnit`

`shortUnit` shortens numbers larger than 1000 by using unit suffixes:

```smarty
{10000|shortUnit} {* prints 10k *}
{5400000|shortUnit} {* prints 5.4M *}
```


### `|tableWordwrap`

`tableWordwrap` inserts zero width spaces every 30 characters in words longer than 30 characters.

```smarty
{$foo|tableWordwrap}
```

### `|time`

!!! info "This template plugin has been deprecated in WoltLab Suite 6.0. Use `{time}` instead."

`time` generates an HTML `time` elements based on a timestamp that shows a relative time or the absolute time if the timestamp more than six days ago.

```smarty
{$timestamp|time} {* prints a '<time>' element *}
```


### `|truncate`

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


## PHP Functions

A limited number of safe native PHP functions may also be used as variable modifiers:

* `abs`
* `addslashes`
* `array_diff`
* `array_fill`
* `array_key_exists`
* `array_keys`
* `array_pop`
* `array_slice`
* `array_values`
* `base64_decode`
* `base64_encode`
* `basename`
* `ceil`
* `concat`
* `constant`
* `count`
* `currency`
* `current`
* `date`
* `defined`
* `doubleval`
* `empty`
* `end`
* `explode`
* `file_exists`
* `filesize`
* `floatval`
* `floor`
* `function_exists`
* `get_class`
* `gmdate`
* `hash`
* `htmlspecialchars`
* `html_entity_decode`
* `http_build_query`
* `implode`
* `in_array`
* `is_array`
* `is_null`
* `is_numeric`
* `is_object`
* `is_string`
* `iterator_count`
* `intval`
* `is_subclass_of`
* `isset`
* `json_encode`
* `key`
* `lcfirst`
* `ltrim`
* `max`
* `mb_strpos`
* `mb_strlen`
* `mb_strpos`
* `mb_strtolower`
* `mb_strtoupper`
* `mb_substr`
* `md5`
* `method_exists`
* `microtime`
* `min`
* `nl2br`
* `number_format`
* `parse_url`
* `preg_match`
* `preg_replace`
* `print_r`
* `random_int`
* `rawurlencode`
* `reset`
* `round`
* `sha1`
* `spl_object_hash`
* `sprintf`
* `strip_tags`
* `strlen`
* `strpos`
* `strtolower`
* `strtotime`
* `strtoupper`
* `str_contains`
* `str_ends_with`
* `str_ireplace`
* `str_pad`
* `str_repeat`
* `str_replace`
* `str_starts_with`
* `substr`
* `trim`
* `ucfirst`
* `uniqid`
* `urlencode`
* `var_dump`
* `version_compare`
* `wcfDebug`
* `wordwrap`
