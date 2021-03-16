# Migrating from WSC 5.3 - JavaScript

## `WCF_CLICK_EVENT`

For event listeners on click events, `WCF_CLICK_EVENT` is deprecated and should no longer be used.
Instead, use `click` directly:

```javascript
// before
element.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));

// after
element.addEventListener('click', (ev) => this._click(ev));
```

## `WCF.Table.EmptyTableHandler`

When all objects in a table or list are deleted via their delete button or clipboard actions, an empty table or list can remain.
Previously, `WCF.Table.EmptyTableHandler` had to be explicitly used in each template for these tables and lists to reload the page.
As a TypeScript-based replacement for `WCF.Table.EmptyTableHandler` that is only initialized once globally, `WoltLabSuite/Core/Ui/Empty` was added.
To use this new module, you only have to add the CSS class `jsReloadPageWhenEmpty` to the relevant HTML element.
Once this HTML element no longer has child elements, the page is reloaded.
To also cover scenarios in which there are fixed child elements that should not be considered when determining if there are no child elements, the `data-reload-page-when-empty="ignore"` can be set for this element.

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
