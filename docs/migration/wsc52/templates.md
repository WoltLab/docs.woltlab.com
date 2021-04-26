# Migrating from WSC 5.2 - Templates and Languages

## `{jslang}`

Starting with WoltLab Suite 5.3 the `{jslang}` template plugin is available.
`{jslang}` works like `{lang}`, with the difference that the result is automatically encoded for use within a single quoted JavaScript string.

Before:

```smarty
<script>
require(['Language', /* … */], function(Language, /* … */) {
    Language.addObject({
        'app.foo.bar': '{lang}app.foo.bar{/lang}',
    });

    // …
});
</script>
```

After:

```smarty
<script>
require(['Language', /* … */], function(Language, /* … */) {
    Language.addObject({
        'app.foo.bar': '{jslang}app.foo.bar{/jslang}',
    });

    // …
});
</script>
```

## Template Plugins

The [`{anchor}`](../../view/template-plugins.md#53-anchor), [`{plural}`](../../view/template-plugins.md#53-plural), and [`{user}`](../../view/template-plugins.md#53-user) template plugins have been added.

## Notification Language Items

In addition to using the new template plugins mentioned above, language items for notifications have been further simplified.

As the whole notification is clickable now, all `a` elements have been replaced with `strong` elements in notification messages.

The template code to output reactions has been simplified by introducing helper methods:

```smarty
{* old *}
{implode from=$reactions key=reactionID item=count}{@$__wcf->getReactionHandler()->getReactionTypeByID($reactionID)->renderIcon()}×{#$count}{/implode}
{* new *}
{@$__wcf->getReactionHandler()->renderInlineList($reactions)}

{* old *}
<span title="{$like->getReactionType()->getTitle()}" class="jsTooltip">{@$like->getReactionType()->renderIcon()}</span>
{* new *}
{@$like->render()}
```

Similarly, showing labels is now also easier due to the new `render` method:

```smarty
{* old *}
<span class="label badge{if $label->getClassNames()} {$label->getClassNames()}{/if}">{$label->getTitle()}</span>
{* new *}
{@$label->render()}
```

The commonly used template code

```smarty
{if $count < 4}{@$authors[0]->getAnchorTag()}{if $count != 1}{if $count == 2 && !$guestTimesTriggered} and {else}, {/if}{@$authors[1]->getAnchorTag()}{if $count == 3}{if !$guestTimesTriggered} and {else}, {/if} {@$authors[2]->getAnchorTag()}{/if}{/if}{if $guestTimesTriggered} and {if $guestTimesTriggered == 1}a guest{else}guests{/if}{/if}{else}{@$authors[0]->getAnchorTag()}{if $guestTimesTriggered},{else} and{/if} {#$others} other users {if $guestTimesTriggered}and {if $guestTimesTriggered == 1}a guest{else}guests{/if}{/if}{/if}
```

in stacked notification messages can be replaced with a new language item:

```smarty
{@'wcf.user.notification.stacked.authorList'|language}
```

## Popovers

Popovers provide additional information of the linked object when a user hovers over a link.
We unified the approach for such links:

1. The relevant DBO class implements `wcf\data\IPopoverObject`.
2. The relevant DBO action class implements `wcf\data\IPopoverAction` and the `getPopover()` method returns an array with popover content.
3. Globally available, `WoltLabSuite/Core/Controller/Popover` is initialized with the relevant data.
4. Links are created with the `anchor` template plugin with an additional `class` attribute whose value is the return value of `IPopoverObject::getPopoverLinkClass()`.

Example:

{jinja{ codebox(
    language="php",
    title="files/lib/data/foo/Foo.class.php",
    contents="""
class Foo extends DatabaseObject implements IPopoverObject {
    public function getPopoverLinkClass() {
        return 'fooLink';
    }
}
"""
)}}

{jinja{ codebox(
    language="php",
    title="files/lib/data/foo/FooAction.class.php",
    contents="""
class FooAction extends AbstractDatabaseObjectAction implements IPopoverAction {
    public function validateGetPopover() {
        // …
    }
    
    public function getPopover() {
        return [
            'template' => '…',
        ];
    }
}
"""
)}}

```js
require(['WoltLabSuite/Core/Controller/Popover'], function(ControllerPopover) {
	ControllerPopover.init({
		className: 'fooLink',
		dboAction: 'wcf\\data∑foo\\FooAction',
		identifier: 'com.woltlab.wcf.foo'
	});
});
```

```smarty
{anchor object=$foo class='fooLink'}
```
