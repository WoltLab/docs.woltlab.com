---
title: "Tutorial Series Part 3: Person Page and Comments"
sidebar: sidebar
permalink: tutorial_tutorial-series_part-3-person-page-and-comments.html
folder: tutorial/tutorial-series
parent: tutorial_tutorial-series
---

In this part of our tutorial series, we will add a new front end page to our package that is dedicated to each person and shows their personal details.
To make good use of this new page and introduce a new API of WoltLab Suite, we will add the opportunity for users to comment on the person using WoltLab Suite’s reusable comment functionality.


## Package Functionality

In addition to the existing functions from [part 1](tutorial_tutorial-series_part-1-base-structure.html), the package will provide the following possibilities/functions after this part of the tutorial:

- Details page for each person linked in the front end person list
- Comment on people on their respective page (can be disabled per person)
- User online location for person details page with name and link to person details page
- Create menu items linking to specific person details pages


## Used Components

In addition to the components used in [part 1](tutorial_tutorial-series_part-1-base-structure.html), we will use the [objectType package installation plugin](package_pip_object-type.html), use the [comment API](php_api_comments.html), create a [runtime cache](php_api_caches_runtime-caches.html), and create a page handler.


## Package Structure

The complete package will have the following file structure (including the files from [part 1](tutorial_tutorial-series_part-1-base-structure.html)):

```
├── acpMenu.xml
├── acptemplates
│   ├── personAdd.tpl
│   └── personList.tpl
├── files
│   └── lib
│       ├── acp
│       │   ├── form
│       │   │   ├── PersonAddForm.class.php
│       │   │   └── PersonEditForm.class.php
│       │   └── page
│       │       └── PersonListPage.class.php
│       ├── data
│       │   └── person
│       │       ├── Person.class.php
│       │       ├── PersonAction.class.php
│       │       ├── PersonEditor.class.php
│       │       └── PersonList.class.php
│       ├── page
│       │   ├── PersonListPage.class.php
│       │   └── PersonPage.class.php
│       └── system
│           ├── cache
│           │   └── runtime
│           │       └── PersonRuntimeCache.class.php
│           ├── comment
│           │   └── manager
│           │       └── PersonCommentManager.class.php
│           └── page
│               └── handler
│                   └── PersonPageHandler.class.php
├── install.sql
├── language
│   ├── de.xml
│   └── en.xml
├── menuItem.xml
├── objectType.xml
├── package.xml
├── page.xml
├── templates
│   ├── person.tpl
│   └── personList.tpl
└── userGroupOption.xml
```

{% include callout.html content="We will not mention every code change between the first part and this part, as we only want to focus on the important, new parts of the code. For example, there is a new `Person::getLink()` method and new language items have been added. For all changes, please refer to the [source code on GitHub](https://github.com/WoltLab/woltlab.github.io/tree/master/_includes/tutorial/tutorial-series/part-3)." type="warning" %}


## Runtime Cache

To reduce the number of database queries when different APIs require person objects, we implement a [runtime cache](php_api_caches_runtime-caches.html) for people:

{% highlight php %}
{% include tutorial/tutorial-series/part-3/files/lib/system/cache/runtime/PersonRuntimeCache.class.php %}
{% endhighlight %}


## Comments

To allow users to comment on people, we need to tell the system that people support comments.
This is done by registering a `com.woltlab.wcf.comment.commentableContent` object type whose processor implements [ICommentManager](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/comment/manager/ICommentManager.class.php):

{% highlight xml %}
{% include tutorial/tutorial-series/part-3/objectType.xml %}
{% endhighlight %}

The `PersonCommentManager` class extended `ICommentManager`’s default implementation [AbstractCommentManager](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/comment/manager/AbstractCommentManager.class.php):

{% highlight php %}
{% include tutorial/tutorial-series/part-3/files/lib/system/comment/manager/PersonCommentManager.class.php %}
{% endhighlight %}

- First, the system is told the names of the permissions via the `$permission*` properties.
  More information about comment permissions can be found [here](php_api_comments.html#user-group-options).
- The `getLink()` method returns the link to the person with the passed comment id.
  As in `isAccessible()`, `PersonRuntimeCache` is used to potentially save database queries.
- The `isAccessible()` method checks if the active user can access the relevant person.
  As we do not have any special restrictions for accessing people, we only need to check if the person exists.
- The `getTitle()` method returns the title used for comments and responses, which is just a generic language item in this case.
- The `updateCounter()` updates the comments’ counter of the person.
  We have added a new `comments` database table column to the `wcf1_person` database table in order to keep track on the number of comments.

Additionally, we have added a new `enableComments` database table column to the `wcf1_person` database table whose value can be set when creating or editing a person in the ACP.
With this option, comments on individual people can be disabled.

{% include callout.html content="Liking comments is already built-in and only requires some extra code in the `PersonPage` class for showing the likes of pre-loaded comments." type="info" %}


## Person Page

### `PersonPage`

{% highlight php %}
{% include tutorial/tutorial-series/part-3/files/lib/page/PersonPage.class.php %}
{% endhighlight %}

The `PersonPage` class is similar to the `PersonEditForm` in the ACP in that it reads the id of the requested person from the request data and validates the id in `readParameters()`.
The rest of the code only handles fetching the list of comments on the requested person.
In `readData()`, this list is fetched using `CommentHandler::getCommentList()` if comments are enabled for the person.
The `assignVariables()` method assigns some additional template variables like `$commentCanAdd`, which is `1` if the active person can add comments and is `0` otherwise, `$lastCommentTime`, which contains the UNIX timestamp of the last comment, and `$likeData`, which contains data related to the likes for the disabled comments.

### `person.tpl`

{% highlight tpl %}
{% include tutorial/tutorial-series/part-3/templates/person.tpl %}
{% endhighlight %}

For now, the `person` template is still very empty and only shows the comments in the content area.
The template code shown for comments is very generic and used in this form in many locations as it only sets the header of the comment list and the container `ul#personCommentList` element for the comments shown by `commentList` template.
The `ul#personCommentList` elements has five additional `data-` attributes required by the JavaScript API for comments for loading more comments or creating new ones.
The `commentListAddComment` template adds the WYSIWYG support.
The attribute `wysiwygSelector` should be the id of the comment list `personCommentList` with an additional `AddComment` suffix.

### `page.xml`

{% highlight xml %}
{% include tutorial/tutorial-series/part-3/page.xml %}
{% endhighlight %}

The `page.xml` file has been extended for the new person page with identifier `com.woltlab.wcf.people.Person`.
Compared to the pre-existing `com.woltlab.wcf.people.PersonList` page, there are four differences:

1. It has a `<handler>` element with a class name as value.
   This aspect will be discussed in more detail in the next section.
1. There are no `<content>` elements because, both, the title and the content of the page are dynamically generated in the template.
1. The `<requireObjectID>` tells the system that this page requires an object id to properly work, in this case a valid person id.
1. This page has a `<parent>` page, the person list page.
   In general, the details page for any type of object that is listed on a different page has the list page as its parent.

### `PersonPageHandler`

{% highlight php %}
{% include tutorial/tutorial-series/part-3/files/lib/system/page/handler/PersonPageHandler.class.php %}
{% endhighlight %}

Like any page handler, the `PersonPageHandler` class has to implement the [IMenuPageHandler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/page/handler/IMenuPageHandler.class.php) interface, which should be done by extending the [AbstractMenuPageHandler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/page/handler/AbstractMenuPageHandler.class.php) class.
As we want  administrators to link to specific people in menus, for example, we have to also implement the [ILookupPageHandler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/page/handler/ILookupPageHandler.class.php) interface by extending the [AbstractLookupPageHandler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/page/handler/AbstractLookupPageHandler.class.php) class.

For the `ILookupPageHandler` interface, we need to implement three methods:

1. `getLink($objectID)` returns the link to the person page with the given id.
   In this case, we simply delegate this method call to the `Person` object returned by `PersonRuntimeCache::getObject()`.
1. `isValid($objectID)` returns `true` if the person with the given id exists, otherwise `false`.
   Here, we use `PersonRuntimeCache::getObject()` again and check if the return value is `null`, which is the case for non-existing people.
1. `lookup($searchString)` is used when setting up an internal link and when searching for the linked person.
   This method simply searches the first and last name of the people and returns an array with the person data.
   While the `link`, the `objectID`, and the `title` element are self-explanatory, the `image` element can either contain an HTML `<img>` tag, which is displayed next to the search result (WoltLab Suite uses an image tag for users showing their avatar, for example), or a FontAwesome icon class (starting with `fa-`).

Additionally, the class also implements [IOnlineLocationPageHandler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/page/handler/IOnlineLocationPageHandler.class.php) which is used to determine the online location of users.
To ensure upwards-compatibility if the `IOnlineLocationPageHandler` interface changes, the [TOnlineLocationPageHandler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/page/handler/TOnlineLocationPageHandler.class.php) trait is used.
The `IOnlineLocationPageHandler` interface requires two methods to be implemented:

1. `getOnlineLocation(Page $page, UserOnline $user)` returns the textual description of the online location.
   The language item for the user online locations should use the pattern `wcf.page.onlineLocation.{page identifier}`.
1. `prepareOnlineLocation(Page $page, UserOnline $user)` is called for each user online before the `getOnlineLocation()` calls.
   In this case, calling `prepareOnlineLocation()` first enables us to add all relevant person ids to the person runtime cache so that for all `getOnlineLocation()` calls combined, only one database query is necessary to fetch all person objects.

---

This concludes the third part of our tutorial series after which each person has a dedicated page on which people can comment on the person.

The complete source code of this part can be found on [GitHub](https://github.com/WoltLab/woltlab.github.io/tree/master/_includes/tutorial/tutorial-series/part-3).

