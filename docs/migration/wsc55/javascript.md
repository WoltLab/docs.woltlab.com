# Migrating from WoltLab Suite 5.5 - TypeScript and JavaScript

## Minimum requirements

The ECMAScript target version has been increased to ES2022 from es2017.

## Subscribe Button (WCF.User.ObjectWatch.Subscribe)

We have replaced the old jQuery-based `WCF.User.ObjectWatch.Subscribe` with a more modern replacement `WoltLabSuite/Core/Ui/User/ObjectWatch`.

The new implementation comes with a ready-to-use template (`__userObjectWatchButton`) for use within `contentInteractionButtons`:
```smarty
{include file='__userObjectWatchButton' isSubscribed=$isSubscribed objectType='foo.bar' objectID=$id}
```

See [WoltLab/WCF#4962](https://github.com/WoltLab/WCF/pull/4962/) for details.

## Support for Legacy Inheritance

The migration from JavaScript to TypeScript was a breaking change because the previous prototype based inheritance was incompatible with ES6 classes.
`Core.enableLegacyInheritance()` was added in an effort to emulate the previous behavior to aid in the migration process.

This workaround was unstable at best and was designed as a temporary solution only.
[WoltLab/WCF#5041](https://github.com/WoltLab/WCF/pull/5041) removed the legacy inheritance, requiring all depending implementations to migrate to ES6 classes.
