# Migrating from WoltLab Suite 5.5 - Templates

## Template Modifiers

WoltLab Suite featured a strict allow-list for template modifiers within the enterprise mode since 5.2.
This allow-list has proved to be a reliable solution against malicious templates.
To improve security and to reduce the number of differences between enterprise mode and non-enterprise mode the allow-list will always be enabled going forward.

It is strongly recommended to keep the template logic as simple as possible by moving the heavy lifting into regular PHP code, reducing the number of (specialized) modifiers that need to be applied.

See [WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788) for details.
