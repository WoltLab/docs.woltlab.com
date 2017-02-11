---
title: "Part 2: Event Listeners and Template Listeners"
sidebar: sidebar
permalink: tutorial_tutorial-series_part-2-event-listeners-and-template-listeners.html
folder: tutorial/tutorial-series
parent: tutorial_tutorial-series
---

In the [first part](tutorial_tutorial-series_part-1-base-structure.html) of this tutorial series, we have created the base structure of our people management package.
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

- [acpTemplate package installation plugin](package_pip_acp-template.html),
- [eventListener package installation plugin](package_pip_event-listener.html),
- [file package installation plugin](package_pip_file.html),
- [language package installation plugin](package_pip_language.html),
- [sql package installation plugin](package_pip_sql.html),
- [template package installation plugin](package_pip_template.html),
- [templateListener package installation plugin](package_pip_template-listener.html).

For more information about the event system, please refer to the [dedicated page on events](php_api_events.html).


## Package Structure

The package will have the following file structure:

```
├── acptemplates
│   └── __personAddBirthday.tpl
├── eventListener.xml
├── files
│   └── lib
│       └── system
│           └── event
│               └── listener
│                   ├── BirthdayPersonAddFormListener.class.php
│                   └── BirthdaySortFieldPersonListPageListener.class.php
├── install.sql
├── language
│   ├── de.xml
│   └── en.xml
├── package.xml
├── templateListener.xml
└── templates
    ├── __personListBirthday.tpl
    └── __personListBirthdaySortField.tpl
```


## Extending Person Model (`install.sql`)

The existing model of a person only contains the person’s first name and their last name (in additional to the id used to identify created people).
To add the birthday to the model, we need to create an additional database table column using the [sql package installation plugin](package_pip_sql.html):

{% highlight sql %}
{% include tutorial/tutorial-series/part-2/install.sql %}
{% endhighlight %}

If we have a [Person object](tutorial_tutorial-series_part-1-base-structure.html#person), this new property can be accessed the same way as the `personID` property, the `firstName` property, or the `lastName` property from the base package: `$person->birthday`.


## Setting Birthday in ACP

To set the birthday of a person, we need to extend the `personAdd` template to add an additional birthday field.
This can be achieved using the `dataFields` template event at whose position we inject the following template code:

{% highlight sql %}
{% include tutorial/tutorial-series/part-2/acptemplates/__personAddBirthday.tpl %}
{% endhighlight %}

which we store in a `__personAddBirthday.tpl` template file.
The used language item `wcf.person.birthday` is actually the only new one for this package:

{% highlight sql %}
{% include tutorial/tutorial-series/part-2/language/de.xml %}
{% endhighlight %}

{% highlight sql %}
{% include tutorial/tutorial-series/part-2/language/en.xml %}
{% endhighlight %}

The template listener needs to be registered using the [templateListener package installation plugin](package_pip_template-listener.html).
The corresponding complete `templateListener.xml` file is included [below](#templatelistenerxml).

The template code alone is not sufficient because the `birthday` field is, at the moment, neither read, nor processed, nor saved by any PHP code.
This can be be achieved, however, by adding event listeners to `PersonAddForm` and `PersonEditForm` which allow us to execute further code at specific location of the program.
Before we take a look at the event listener code, we need to identify exactly which additional steps we need to undertake:

1. If a person is edited and the form has not been submitted, the existing birthday of that person needs to be read.
1. If a person is added or edited and the form has been submitted, the new birthday value needs to be read.
1. If a person is added or edited and the form has been submitted, the new birthday value needs to be validated.
1. If a person is added or edited and the new birthday value has been successfully validated, the new birthday value needs to be saved.
1. If a person is added and the new birthday value has been successfully saved, the internally stored birthday needs to be reset so that the birthday field is empty when the form is shown again.
1. The internally stored birthday value needs to be assigned to the template.

The following event listeners achieves these requirements:

{% highlight php %}
{% include tutorial/tutorial-series/part-2/files/lib/system/event/listener/BirthdayPersonAddFormListener.class.php %}
{% endhighlight %}

Some notes on the code:

- The `execute()` just delegates the calls to the specific methods of the class that have the same name as the event (and here also the same name as the methods in which the events are fired).
  Additionally, we throw a `LogicException` if no such method exists in the class to avoid misuse of the class.
- The `birthday` column has a default value of `0000-00-00`, which we interpret as “birthday not set”.
  To show an empty input field in this case, we empty the `birthday` property after reading such a value in `readData()`.
- The validation of the date is, as mentioned before, very basic and just checks the form of the string and uses PHP’s [checkdate](https://secure.php.net/manual/en/function.checkdate.php) function to validate the components.
- The `save` needs to make sure that the passed date is actually a valid date and set it to `0000-00-00` if no birthday is given.
  To actually save the birthday in the database, we do not directly manipulate the database but can add an additional field to the data array passed to `PersonAction::create()` via `AbstractForm::$additionalFields`.
  As the `save` event is the last event fired before the actual save process happens, this is the perfect event to set this array element.

The event listeners are installed using the `eventListener.xml` file shown [below](#eventlistenerxml).


## Adding Birthday Table Column in ACP

To add a birthday column to the person list page in the ACP, we need three parts:

1. an event listener that makes the `birthday` database table column a valid sort field,
1. a template listener that adds the birthday column to the table’s head, and
1. a template listener that adds the birthday column to the table’s rows.

The first part is a very simple class:

{% highlight php %}
{% include tutorial/tutorial-series/part-2/files/lib/system/event/listener/BirthdaySortFieldPersonListPageListener.class.php %}
{% endhighlight %}

{% include callout.html content="We use `SortablePage` as a type hint instead of `wcf\acp\page\PersonListPage` because we will be using the same event listener class in the front end to also allow sorting that list by birthday." type="info" %}

As the relevant template codes are only one line each, we will simply put them directly in the `templateListener.xml` file that will be shown [later on](#templatelistenerxml).
The code for the table head is similar to the other `th` elements:

```smarty
<th class="columnDate columnBirthday{if $sortField == 'birthday'} active {@$sortOrder}{/if}"><a href="{link controller='PersonList'}pageNo={@$pageNo}&sortField=birthday&sortOrder={if $sortField == 'birthday' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.person.birthday{/lang}</a></th>
```

For the table body’s column, we need to make sure that the birthday is only show if it is actually set:

```smarty
<td class="columnDate columnBirthday">{if $person->birthday !== '0000-00-00'}{@$person->birthday|strtotime|date}{/if}</td>
```


## Adding Birthday in Front End

In the front end, we also want to make the list sortable by birthday and show the birthday as part of each person’s “statistics”.

To add the birthday as a valid sort field, we use `BirthdaySortFieldPersonListPageListener` just as in the ACP.
In the front end, we will now use a template (`__personListBirthdaySortField.tpl`) instead of a directly putting the template code in the `templateListener.xml` file:

{% highlight smarty %}
{% include tutorial/tutorial-series/part-2/templates/__personListBirthdaySortField.tpl %}
{% endhighlight %}

{% include callout.html content="You might have noticed the two underscores at the beginning of the template file. For templates that are included via template listeners, this is the naming convention we use." type="info" %}

Putting the template code into a file has the advantage that in the administrator is able to edit the code directly via a custom template group, even though in this case this might not be very probable.

To show the birthday, we use the following template code for the `personStatistics` template event, which again makes sure that the birthday is only shown if it is actually set:

{% highlight smarty %}
{% include tutorial/tutorial-series/part-2/templates/__personListBirthday.tpl %}
{% endhighlight %}


## `templateListener.xml`

The following code shows the `templateListener.xml` file used to install all mentioned template listeners:

{% highlight xml %}
{% include tutorial/tutorial-series/part-2/templateListener.xml %}
{% endhighlight %}

In cases where a template is used, we simply use the `include` syntax to load the template.


## `eventListener.xml`

There are two event listeners, `birthdaySortFieldAdminPersonList` and `birthdaySortFieldPersonList`, that make `birthday` a valid sort field in the ACP and the front end, respectively, and the rest takes care of setting the birthday.
The event listener `birthdayPersonAddFormInherited` takes care of the events that are relevant for both adding and editing people, thus it listens to the `PersonAddForm` class but has `inherit` set to `1` so that it also listens to the events of the `PersonEditForm` class.
In contrast, reading the existing birthday from a person is only relevant for editing so that the event listener `birthdayPersonEditForm` only listens to that class.

{% highlight xml %}
{% include tutorial/tutorial-series/part-2/eventListener.xml %}
{% endhighlight %}


## `package.xml`

The only relevant difference between the `package.xml` file of the base page from part 1 and the `package.xml` file of this package is that this package requires the base package `com.woltlab.wcf.people` (see `<requiredpackages>`):

{% highlight xml %}
{% include tutorial/tutorial-series/part-2/package.xml %}
{% endhighlight %}

---

This concludes the second part of our tutorial series after which you now have extended the base package using event listeners and template listeners that allow you to enter the birthday of the people.

The complete source code of this part can be found on [GitHub](https://github.com/WoltLab/woltlab.github.io/tree/master/_includes/tutorial/tutorial-series/part-2).
