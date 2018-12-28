---
title: ACL Option Package Installation Plugin
sidebar: sidebar
permalink: package_pip_acl-option.html
folder: package/pip
parent: package_pip
---

Add customizable permissions for individual objects.

## Option Components

Each acl option is described as an `<option>` element with the mandatory attribute `name`.

### `<categoryname>`

<span class="label label-info">Optional</span>

The name of the acl option category to which the option belongs.

### `<objecttype>`

The name of the acl object type (of the object type definition `com.woltlab.wcf.acl`).


## Category Components

Each acl option category is described as an `<category>` element with the mandatory attribute `name`  that should follow the naming pattern `<permissionName>` or `<permissionType>.<permissionName>`, with `<permissionType>` generally having `user` or `mod` as value.

### `<objecttype>`

The name of the acl object type (of the object type definition `com.woltlab.wcf.acl`).


## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/aclOption.xsd">
    <import>
        <categories>
            <category name="user.example">
                <objecttype>com.example.wcf.example</objecttype>
            </category>
            <category name="mod.example">
                <objecttype>com.example.wcf.example</objecttype>
            </category>
        </categories>
        
        <options>
            <option name="canAddExample">
                <categoryname>user.example</categoryname>
                <objecttype>com.example.wcf.example</objecttype>
            </option>
            <option name="canDeleteExample">
                <categoryname>mod.example</categoryname>
                <objecttype>com.example.wcf.example</objecttype>
            </option>
        </options>
    </import>

    <delete>
        <optioncategory name="old.example">
           <objecttype>com.example.wcf.example</objecttype>
        </optioncategory>
        <option name="canDoSomethingWithExample">
           <objecttype>com.example.wcf.example</objecttype>
        </option>
    </delete>
</data>
```
