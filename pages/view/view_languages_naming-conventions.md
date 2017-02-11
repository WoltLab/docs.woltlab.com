---
title: Language Naming Conventions
sidebar: sidebar
permalink: view_languages_naming-conventions.html
folder: view
parent: view_languages
---

This page contains general rules for naming language items and for their values.
API-specific rules are listed on the relevant API page:

- [Comments](php_api_comments.html#language-items)


## Forms

### Fields

If you have an application `foo` and a database object `foo\data\bar\Bar` with a property `baz` that can be set via a form field, the name of the corresponding language item has to be `foo.bar.baz`.
If you want to add an additional description below the field, use the language item `foo.bar.baz.description`.

### Error Texts

If an error of type `{error type}` for the previously mentioned form field occurs during validation, you have to use the language item `foo.bar.baz.error.{error type}` for the language item describing the error.
 
Exception to this rule:
There are several general error messages like `wcf.global.form.error.empty` that have to be used for general errors like an empty field that may not be empty to avoid duplication of the same error message text over and over again in different language items.

#### Naming Conventions

- If the entered text does not conform to some special rules, i.e. if the text is invalid, use `invalid` as error type.
- If the entered text is required to be unique but is already used for another object, use `notUnique` as error type.


## Confirmation messages

If the language item for an action is `foo.bar.action`, the language item for the confirmation message has to be `foo.bar.action.confirmMessage` instead of `foo.bar.action.sure` which is still used by some older language items.

### Type-Specific Deletion Confirmation Message

#### German

```
{if LANGUAGE_USE_INFORMAL_VARIANT}Willst du{else}Wollen Sie{/if} {element type} wirklich löschen?
```

Example:

```
{if LANGUAGE_USE_INFORMAL_VARIANT}Willst du{else}Wollen Sie{/if} das Icon wirklich löschen?
```

#### English

```
Do you really want delete the {element type}?
```

Example:

```
Do you really want delete the icon?
```

### Object-Specific Deletion Confirmation Message

#### German

```
{if LANGUAGE_USE_INFORMAL_VARIANT}Willst du{else}Wollen Sie{/if} {element type} <span class="confirmationObject">{object name}</span> wirklich löschen?
```
  
Example:

```
{if LANGUAGE_USE_INFORMAL_VARIANT}Willst du{else}Wollen Sie{/if} den Artikel <span class="confirmationObject">{$article->getTitle()}</span> wirklich löschen?
```

#### English

```
Do you really want to delete the {element type} <span class="confirmationObject">{object name}</span>?
```

Example:

```
Do you really want to delete the article <span class="confirmationObject">{$article->getTitle()}</span>?
```


## User Group Options

### Comments

#### German

| group type | action | example permission name | language item |
| ---------- | ------ | ----------------------- | ------------- |
| user | adding | `user.foo.canAddComment` | `Kann Kommentare erstellen` |
| user | deleting | `user.foo.canDeleteComment` | `Kann eigene Kommentare löschen` |
| user | editing | `user.foo.canEditComment` | `Kann eigene Kommentare bearbeiten` |
| moderator | deleting | `mod.foo.canDeleteComment` | `Kann Kommentare löschen` |
| moderator | editing | `mod.foo.canEditComment` | `Kann Kommentare bearbeiten` |
| moderator | moderating | `mod.foo.canModerateComment` | `Kann Kommentare moderieren` |

#### English

| group type | action | example permission name | language item |
| ---------- | ------ | ----------------------- | ------------- |
| user | adding | `user.foo.canAddComment` | `Can create comments` |
| user | deleting | `user.foo.canDeleteComment` | `Can delete their comments` |
| user | editing | `user.foo.canEditComment` | `Can edit their comments` |
| moderator | deleting | `mod.foo.canDeleteComment` | `Can delete comments` |
| moderator | editing | `mod.foo.canEditComment` | `Can edit comments` |
| moderator | moderating | `mod.foo.canModerateComment` | `Can moderate comments` |
