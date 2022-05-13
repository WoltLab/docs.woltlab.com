# Migrating from WoltLab Suite 5.3 - TypeScript and JavaScript

## TypeScript

WoltLab Suite 5.4 introduces TypeScript support.
Learn about consuming WoltLab Suite’s types in [the TypeScript section](../../javascript/typescript.md) of the JavaScript API documentation.

The JavaScript API documentation will be updated to properly take into account the changes that came with the new TypeScript support in the future.
Existing AMD based modules have been migrated to TypeScript, but will expose the existing and known API.

It is recommended that you migrate your custom packages to make use of TypeScript.
It will make consuming newly written modules that properly leverage TypeScript’s features much more pleasant and will also ease using existing modules due to proper autocompletion and type checking.


## Replacements for Deprecated Components

The helper functions in `wcf.globalHelper.js` should not be used anymore but replaced by their native counterpart:

| Function | Native Replacement |
|----------|--------------------|
| `elCreate(tag)` | `document.createElement(tag)` |
| `elRemove(el)` | `el.remove()` |
| `elShow(el)` | `DomUtil.show(el)` |
| `elHide(el)` | `DomUtil.hide(el)` |
| `elIsHidden(el)` | `DomUtil.isHidden(el)` |
| `elToggle(el)` | `DomUtil.toggle(el)` |
| `elAttr(el, "attr")` | `el.attr` or `el.getAttribute("attr")` |
| `elData(el, "data")` | `el.dataset.data` |
| `elDataBool(element, "data")` | `Core.stringToBool(el.dataset.data)` |
| `elById(id)` | `document.getElementById(id)` |
| `elBySel(sel)` | `document.querySelector(sel)` |
| `elBySel(sel, el)` | `el.querySelector(sel)` |
| `elBySelAll(sel)` | `document.querySelectorAll(sel)` |
| `elBySelAll(sel, el)` | `el.querySelectorAll(sel)` |
| `elBySelAll(sel, el, callback)` | `el.querySelectorAll(sel).forEach((el) => callback(el));` |
| `elClosest(el, sel)` | `el.closest(sel)` |
| `elByClass(class)` | `document.getElementsByClassName(class)` |
| `elByClass(class, el)` | `el.getElementsByClassName(class)` |
| `elByTag(tag)` | `document.getElementsByTagName(tag)` |
| `elByTag(tag, el)` | `el.getElementsByTagName(tag)` |
| `elInnerError(el, message, isHtml)` | `DomUtil.innerError(el, message, isHtml)` |

Additionally, the following modules should also be replaced by their native counterpart:

| Module | Native Replacement |
|--------|--------------------|
| `WoltLabSuite/Core/Dictionary` | `Map` |
| `WoltLabSuite/Core/List` | `Set` |
| `WoltLabSuite/Core/ObjectMap` | `WeakMap` |

For event listeners on click events, `WCF_CLICK_EVENT` is deprecated and should no longer be used.
Instead, use `click` directly:

```javascript
// before
element.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));

// after
element.addEventListener('click', (ev) => this._click(ev));
```

## `WCF.Action.Delete` and `WCF.Action.Toggle`

`WCF.Action.Delete` and `WCF.Action.Toggle` were used for buttons to delete or enable/disable objects via JavaScript.
In each template, `WCF.Action.Delete` or `WCF.Action.Toggle` instances had to be manually created for each object listing.

With version 5.4 of WoltLab Suite, we have added a CSS selector-based global TypeScript module that only requires specific CSS classes to be added to the HTML structure for these buttons to work.
Additionally, we have added a new `{objectAction}` template plugin, which generates these buttons reducing the amount of boilerplate template code.

The required base HTML structure is as follows:

1. A `.jsObjectActionContainer` element with a `data-object-action-class-name` attribute that contains the name of PHP class that executes the actions.
2. `.jsObjectActionObject` elements within `.jsObjectActionContainer` that represent the objects for which actions can be executed.
   Each `.jsObjectActionObject` element must have a `data-object-id` attribute with the id of the object.
3. `.jsObjectAction` elements within `.jsObjectActionObject` for each action with a `data-object-action` attribute with the name of the action.
   These elements can be generated with the `{objectAction}` template plugin for the `delete` and `toggle` action.

Example:

```smarty
<table class="table jsObjectActionContainer" {*
    *}data-object-action-class-name="wcf\data\foo\FooAction">
    <thead>
        <tr>
            {* … *}
        </tr>
    </thead>
    
    <tbody>
        {foreach from=$objects item=foo}
            <tr class="jsObjectActionObject" data-object-id="{@$foo->getObjectID()}">
                <td class="columnIcon">
                    {objectAction action="toggle" isDisabled=$foo->isDisabled}
                    {objectAction action="delete" objectTitle=$foo->getTitle()}
                    {* … *}
                </td>
                {* … *}
            </tr>
        {/foreach}
    </tbody>
</table>
```

Please refer to the documentation in [`ObjectActionFunctionTemplatePlugin`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/template/plugin/ObjectActionFunctionTemplatePlugin.class.php) for details and examples on how to use this template plugin.

The relevant TypeScript module registering the event listeners on the object action buttons is [`Ui/Object/Action`](https://github.com/WoltLab/WCF/blob/master/ts/WoltLabSuite/Core/Ui/Object/Action.ts).
When an action button is clicked, an AJAX request is sent using the PHP class name and action name.
After the successful execution of the action, the page is either reloaded if the action button has a `data-object-action-success="reload"` attribute or an event using the `EventHandler` module is fired using `WoltLabSuite/Core/Ui/Object/Action` as the identifier and the object action name.
[`Ui/Object/Action/Delete`](https://github.com/WoltLab/WCF/blob/master/ts/WoltLabSuite/Core/Ui/Object/Action/Delete.ts) and [`Ui/Object/Action/Toggle`](https://github.com/WoltLab/WCF/blob/master/ts/WoltLabSuite/Core/Ui/Object/Action/Toggle.ts) listen to these events and update the user interface depending on the execute action by removing the object or updating the toggle button, respectively.

Converting from `WCF.Action.*` to the new approach requires minimal changes per template, as shown in the relevant pull request [#4080](https://github.com/WoltLab/WCF/pull/4080).


## `WCF.Table.EmptyTableHandler`

When all objects in a table or list are deleted via their delete button or clipboard actions, an empty table or list can remain.
Previously, `WCF.Table.EmptyTableHandler` had to be explicitly used in each template for these tables and lists to reload the page.
As a TypeScript-based replacement for `WCF.Table.EmptyTableHandler` that is only initialized once globally, `WoltLabSuite/Core/Ui/Empty` was added.
To use this new module, you only have to add the CSS class `jsReloadPageWhenEmpty` to the relevant HTML element.
Once this HTML element no longer has child elements, the page is reloaded.
To also cover scenarios in which there are fixed child elements that should not be considered when determining if there are no child elements, the `data-reload-page-when-empty="ignore"` can be set for these elements.

Examples:

```smarty
<table class="table">
    <thead>
        <tr>
            {* … *}
        </tr>
    </thead>

    <tbody class="jsReloadPageWhenEmpty">
        {foreach from=$objects item=object}
            <tr>
                {* … *}
            </tr>
        {/foreach}
    </tbody>
</table>
```

```smarty
<div class="section tabularBox messageGroupList">
    <ol class="tabularList jsReloadPageWhenEmpty">
        <li class="tabularListRow tabularListRowHead" data-reload-page-when-empty="ignore">
            {* … *}
        </li>

        {foreach from=$objects item=object}
            <li>
                {* … *}
            </li>
        {/foreach}
    </ol>
</div>
```
