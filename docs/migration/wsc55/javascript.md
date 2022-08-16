# Migrating from WoltLab Suite 5.5 - TypeScript and JavaScript

## Minimum requirements

The ECMAScript target version has been increased to es2019 from es2017.

## Subscribe Button (WCF.User.ObjectWatch.Subscribe)

We have replaced the old jQuery-based `WCF.User.ObjectWatch.Subscribe` with a more modern replacement `WoltLabSuite/Core/Ui/User/ObjectWatch`.

The new implementation comes with a ready-to-use template (`__userObjectWatchButton`) for use within `contentInteractionButtons`:
```smarty
{include file='__userObjectWatchButton' isSubscribed=$isSubscribed objectType='foo.bar' objectID=$id}
```

See [WoltLab/WCF#4962](https://github.com/WoltLab/WCF/pull/4962/) for details.
