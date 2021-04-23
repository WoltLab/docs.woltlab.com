# Part 2: Event and Template Listeners

In the [first part](part_1.md) of this tutorial series, we have created the base structure of our people management package.
In further parts, we will use the package of the first part as a basis to directly add new features.
In order to explain how event listeners and template works, however, we will not directly adding a new feature to the package by altering it in this part, but we will assume that somebody else created the package and that we want to extend it the “correct” way by creating a plugin.

The goal of the small plugin that will be created in this part is to add the birthday of the managed people.
As in the first part, we will not bother with careful validation of the entered date but just make sure that it is a valid date.


## Package Functionality

The package should provide the following possibilities/functions:

- List person’s birthday (if set) in people list in the ACP
- Sort people list by birthday in the ACP
- Add or remove birthday when adding or editing person
- List person’s birthday (if set) in people list in the front end
- Sort people list by birthday in the front end


## Used Components

We will use the following package installation plugins:

- [database package installation plugin](../../package/pip/database.md),
- [eventListener package installation plugin](../../package/pip/event-listener.md),
- [file package installation plugin](../../package/pip/file.md),
- [language package installation plugin](../../package/pip/language.md),
- [template package installation plugin](../../package/pip/template.md),
- [templateListener package installation plugin](../../package/pip/template-listener.md).

For more information about the event system, please refer to the [dedicated page on events](../../php/api/events.md).


## Package Structure

The package will have the following file structure:

```
├── eventListener.xml
├── files
│   ├── acp
│   │   └── database
│   │       └── install_com.woltlab.wcf.people.birthday.php
│   └── lib
│       └── system
│           └── event
│               └── listener
│                   ├── BirthdayPersonAddFormListener.class.php
│                   └── BirthdaySortFieldPersonListPageListener.class.php
├── language
│   ├── de.xml
│   └── en.xml
├── package.xml
├── templateListener.xml
└── templates
    ├── __personListBirthday.tpl
    └── __personListBirthdaySortField.tpl
```


## Extending Person Model

The existing model of a person only contains the person’s first name and their last name (in additional to the id used to identify created people).
To add the birthday to the model, we need to create an additional database table column using the [`database` package installation plugin](../../package/pip/database.md):

{jinja{ codebox(
    "sql",
    "tutorial/tutorial-series/part-2/files/acp/database/install_com.woltlab.wcf.people.birthday.php",
    "files/acp/database/install_com.woltlab.wcf.people.birthday.php"
) }}

If we have a [`Person` object](part_1.md#person), this new property can be accessed the same way as the `personID` property, the `firstName` property, or the `lastName` property from the base package: `$person->birthday`.


## Setting Birthday in ACP

To set the birthday of a person, we only have to add another form field with an event listener:

{jinja{ codebox(
    "php",
    "tutorial/tutorial-series/part-2/files/lib/system/event/listener/BirthdayPersonAddFormListener.class.php",
    "files/lib/system/event/listener/BirthdayPersonAddFormListener.class.php"
) }}

registered via

```xml
<eventlistener name="createForm@wcf\acp\form\PersonAddForm">
  <environment>admin</environment>
  <eventclassname>wcf\acp\form\PersonAddForm</eventclassname>
  <eventname>createForm</eventname>
  <listenerclassname>wcf\system\event\listener\BirthdayPersonAddFormListener</listenerclassname>
  <inherit>1</inherit>
</eventlistener>
```

in `eventListener.xml`, [see below](#eventlistenerxml).

As `BirthdayPersonAddFormListener` extends `AbstractEventListener` and as the name of relevant event is `createForm`, `AbstractEventListener` internally automatically calls `onCreateForm()` with the event object as the parameter.
It is important to set `<inherit>1</inherit>` so that the event listener is also executed for `PersonEditForm`, which extends `PersonAddForm`.

The language item `wcf.person.birthday` used in the label is the only new one for this package:

{jinja{ codebox(
    "sql",
    "tutorial/tutorial-series/part-2/language/de.xml",
    "language/de.xml"
) }}

{jinja{ codebox(
    "sql",
    "tutorial/tutorial-series/part-2/language/en.xml",
    "language/en.xml"
) }}


## Adding Birthday Table Column in ACP

To add a birthday column to the person list page in the ACP, we need three parts:

1. an event listener that makes the `birthday` database table column a valid sort field,
1. a template listener that adds the birthday column to the table’s head, and
1. a template listener that adds the birthday column to the table’s rows.

The first part is a very simple class:

{jinja{ codebox(
    "php",
    "tutorial/tutorial-series/part-2/files/lib/system/event/listener/BirthdaySortFieldPersonListPageListener.class.php",
    "files/lib/system/event/listener/BirthdaySortFieldPersonListPageListener.class.php"
) }}

!!! info "We use `SortablePage` as a type hint instead of `wcf\acp\page\PersonListPage` because we will be using the same event listener class in the front end to also allow sorting that list by birthday."

As the relevant template codes are only one line each, we will simply put them directly in the `templateListener.xml` file that will be shown [later on](#templatelistenerxml).
The code for the table head is similar to the other `th` elements:

```smarty
<th class="columnDate columnBirthday{if $sortField == 'birthday'} active {@$sortOrder}{/if}"><a href="{link controller='PersonList'}pageNo={@$pageNo}&sortField=birthday&sortOrder={if $sortField == 'birthday' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.person.birthday{/lang}</a></th>
```

For the table body’s column, we need to make sure that the birthday is only show if it is actually set:

```smarty
<td class="columnDate columnBirthday">{if $person->birthday}{@$person->birthday|strtotime|date}{/if}</td>
```


## Adding Birthday in Front End

In the front end, we also want to make the list sortable by birthday and show the birthday as part of each person’s “statistics”.

To add the birthday as a valid sort field, we use `BirthdaySortFieldPersonListPageListener` just as in the ACP.
In the front end, we will now use a template (`__personListBirthdaySortField.tpl`) instead of a directly putting the template code in the `templateListener.xml` file:

{jinja{ codebox(
    "smarty",
    "tutorial/tutorial-series/part-2/templates/__personListBirthdaySortField.tpl",
    "templates/__personListBirthdaySortField.tpl"
) }}

!!! info "You might have noticed the two underscores at the beginning of the template file. For templates that are included via template listeners, this is the naming convention we use."

Putting the template code into a file has the advantage that in the administrator is able to edit the code directly via a custom template group, even though in this case this might not be very probable.

To show the birthday, we use the following template code for the `personStatistics` template event, which again makes sure that the birthday is only shown if it is actually set:

{jinja{ codebox(
    "smarty",
    "tutorial/tutorial-series/part-2/templates/__personListBirthday.tpl",
    "templates/__personListBirthday.tpl"
) }}


## `templateListener.xml`

The following code shows the `templateListener.xml` file used to install all mentioned template listeners:

{jinja{ codebox(
    "xml",
    "tutorial/tutorial-series/part-2/templateListener.xml",
    "templateListener.xml"
) }}

In cases where a template is used, we simply use the `include` syntax to load the template.


## `eventListener.xml`

There are two event listeners that make `birthday` a valid sort field in the ACP and the front end, respectively, and the third event listener takes care of setting the birthday.

{jinja{ codebox(
    "xml",
    "tutorial/tutorial-series/part-2/eventListener.xml",
    "eventListener.xml"
) }}


## `package.xml`

The only relevant difference between the `package.xml` file of the base page from part 1 and the `package.xml` file of this package is that this package requires the base package `com.woltlab.wcf.people` (see `<requiredpackages>`):

{jinja{ codebox(
    "xml",
    "tutorial/tutorial-series/part-2/package.xml",
    "package.xml"
) }}

---

This concludes the second part of our tutorial series after which you now have extended the base package using event listeners and template listeners that allow you to enter the birthday of the people.

The complete source code of this part can be found on [GitHub]({jinja{ config.repo_url }}tree/{jinja{ config.edit_uri.split("/")[1] }}/snippets/tutorial/tutorial-series/part-2).
