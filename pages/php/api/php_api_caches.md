---
title: Caches
sidebar: sidebar
permalink: php_api_caches.html
folder: php/api
---

WoltLab Suite offers two distinct types of caches:

1. [Persistent caches](php_api_caches_persistent-caches.html) created by cache builders whose data can be stored using different cache sources.
2. [Runtime caches](php_api_caches_runtime-caches.html) store objects for the duration of a single request.

## Understanding Caching

Every so often, plugins make use of cache builders or runtime caches to store
their data, even if there is absolutely no need for them to do so. Usually, this
involves a strong opinion about the total number of SQL queries on a page,
including but not limited to some magic treshold numbers, which should not be
exceeded for "performance reasons".

This misconception can easily lead into thinking that SQL queries should be
avoided or at least written to a cache, so that it doesn't need to be executed
so often. Unfortunately, this completely ignores the fact that both a single
query can take down your app (e. g. full table scan on millions of rows), but
10 queries using a primary key on a table with a few hundred rows will not slow
down your page.

There are some queries that should go into caches by design, but most of the
cache builders weren't initially there, but instead have been added because
they were required to reduce the load _significantly_. You need to understand
that caches always come at a cost, even a runtime cache does! In particular,
they will always consume memory that is not released over the duration of the
request lifecycle and potentially even leak memory by holding references to
objects and data structures that are no longer required.

Caching should always be a solution for a problem.

### When to Use a Cache

It's difficult to provide a definite answer or checklist when to use a cache
and why it is required at this point, because the answer is: It depends. The
permission cache for user groups is a good example for a valid cache, where
we can achieve significant performance improvement compared to processing this
data on every request.

Its caches are build for each permutation of user group memberships that are
encountered for a page request. Building this data is an expensive process that
involves both inheritance and specific rules in regards to when a value for a
permission overrules another value. The added benefit of this cache is that one
cache usually serves a large number of users with the same group memberships and
by computing these permissions once, we can serve many different requests. Also,
the permissions are rather static values that change very rarely and thus we can
expect a very high cache lifetime before it gets rebuild.

### When not to Use a Cache

I remember, a few years ago, there was a plugin that displayed a user's character
from an online video game. The character sheet not only included a list of basic
statistics, but also displayed the items that this character was wearing and or
holding at the time.

The data for these items were downloaded in bulk from the game's vendor servers
and stored in a persistent cache file that periodically gets renewed. There is
nothing wrong with the idea of caching the data on your own server rather than
requesting them everytime from the vendor's servers - not only because they
imposed a limit on the number of requests per hour.

Unfortunately, the character sheet had a sub-par performance and the users were
upset by the significant loading times compared to literally every other page
on the same server. The author of the plugin was working hard to resolve this
issue and was evaluating all kind of methods to improve the page performance,
including deep-diving into the realm of micro-optimizations to squeeze out every
last bit of performance that is possible.

The real problem was the cache file itself, it turns out that it was holding the
data for several thousand items with a total file size of about 13 megabytes.
It doesn't look that much at first glance, after all this isn't the '90s anymore,
but unserializing a 13 megabyte array is really slow and looking up items in such
a large array isn't exactly fast either.

The solution was rather simple, the data that was fetched from the vendor's API
was instead written into a separate database table. Next, the persistent cache
was removed and the character sheet would now request the item data for that
specific character straight from the database. Previously, the character sheet
took several seconds to load and after the change it was done in a fraction of
a second. Although quite extreme, this illustrates a situation where the cache
file was introduced in the design process, without evaluating if the cache -
at least how it was implemented - was really necessary.

Caching should always be a solution for a problem. Not the other way around.

{% include links.html %}
