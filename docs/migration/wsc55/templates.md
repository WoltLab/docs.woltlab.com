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
