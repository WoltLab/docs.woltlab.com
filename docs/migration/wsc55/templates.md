# Migrating from WoltLab Suite 5.5 - Templates

## Template Modifiers

WoltLab Suite featured a strict allow-list for template modifiers within the enterprise mode since 5.2.
This allow-list has proved to be a reliable solution against malicious templates.
To improve security and to reduce the number of differences between enterprise mode and non-enterprise mode the allow-list will always be enabled going forward.

It is strongly recommended to keep the template logic as simple as possible by moving the heavy lifting into regular PHP code, reducing the number of (specialized) modifiers that need to be applied.

See [WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788) for details.

## Time Rendering

The `|time`, `|plainTime` and `|date` modifiers have been deprecated and replaced by a unified `{time}` function.

The main benefit is that it is no longer necessary to specify the `@` symbol when rendering the interactive time element, making it easier to perform a security review of templates by searching for the `@` symbol.

See [WoltLab/WCF#5459](https://github.com/WoltLab/WCF/pull/5459) for details.

## Comments

In WoltLab Suite 6.0 the comment system has been overhauled.
In the process, the integration of comments via templates has been significantly simplified:

```smarty
{include file='comments' commentContainerID='someElementId' commentObjectID=$someObjectID}
```

An example for the migration of existing template integrations can be found [here](https://github.com/WoltLab/WCF/commit/b1d5f7cc6b81ae7fd938603bb20a3a454a531a96#diff-3419ed2f17fa84a70caf0d99511d5ac2a7704c62f24cc7042984d7a9932525ce).

See [WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210) for more details.

## The `<button>` Element

The styling of the `<button>` has been completely removed in WoltLab Suite 6.0 and the element has no longer any kind of styling.
This change allows the element to be used in a lot of places that previously had to use an `a[href="#"][role="button"]` to replicate the same behavior.

If you have previously used the button element as an actual button, you should add the CSS class `.button` to it.

It is recommended to identify an uses of the anchor element to replicate a native button and to use the proper `<button>` element from now on.
Buttons will implicitly submit a form, therefore you should set the `type` attribute to explicitly define its behavior.

```html
<form method="post">
    <!-- These two buttons will submit the form. -->
    <button>Button 1</button>
    <button type="submit">Button 2</button>

    <!-- Clicking this button does nothing on its own. -->
    <button type="button">Button 3</button>
</form>
```

See [WoltLab/WCF#4834](https://github.com/WoltLab/WCF/issues/4834) for more details.

## Pagination

The `{pages}` template function has been deprecated and replaced by the new [`<woltlab-core-pagination>` web component](../../javascript/components_pagination.md).

If you continue to use the `{pages}` template function, it will automatically generate the code for the web component. 

See [WoltLab/WCF#5158](https://github.com/WoltLab/WCF/pull/5158) for more details.
