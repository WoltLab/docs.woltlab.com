---
title: Data Structures - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_data-structures.html
folder: javascript
---

## Introduction

JavaScript offers only limited types of collections to hold and iterate over
data. Despite the ongoing efforts in ES6 and newer, these new data structures
and access methods, such as `for … of`, are not available in the still supported
Internet Explorer 11.

## `Dictionary`

Represents a simple key-value map, but unlike the use of plain objects, will
always to guarantee to iterate over directly set values only.

_In supported browsers this will use a native `Map` internally, otherwise a plain object._

### `set(key: string, value: any)`

Adds or updates an item using the provided key. Numeric keys will be converted
into strings.

### `delete(key: string)`

Removes an item from the collection.

### `has(key: string): boolean`

Returns true if the key is contained in the collection.

### `get(key: string): any`

Returns the value for the provided key, or `undefined` if the key was not found.
Use `.has()` to check for key existence.

### `forEach(callback: (value: any, key: string) => void)`

Iterates over all items in the collection in an arbitrary order and invokes the
supplied callback with the value and the key.

### `size: number`

This read-only property counts the number of items in the collection.

## `List`

Represents a list of unique values.

_In supported browsers this will use a native `Set` internally, otherwise an array._

### `add(value: any)`

Adds a value to the list. If the value is already part of the list, this method
will silently abort.

### `clear()`

Resets the collection.

### `delete(value: any): boolean`

Attempts to remove a value from the list, it returns true if the value has been
part of the list.

### `forEach(callback: (value: any) => void)`

Iterates over all values in the list in an arbitrary order and invokes the
supplied callback for each value.

### `has(value: any): boolean`

Returns true if the provided value is part of this list.

### `size: number`

This read-only property counts the number of items in the list.

## `ObjectMap`

{% include callout.html content="This class uses a `WeakMap` internally, the keys are only weakly referenced and do not prevent garbage collection." type="info" %}

Represents a collection where any kind of objects, such as class instances or
DOM elements, can be used as key. These keys are weakly referenced and will not
prevent garbage collection from happening, but this also means that it is not
possible to enumerate or iterate over the stored keys and values.

This class is especially useful when you want to store additional data for
objects that may get disposed on runtime, such as DOM elements. Using any regular
data collections will cause the object to be referenced indefinitely, preventing
the garbage collection from removing orphaned objects.

### `set(key: Object, value: Object)`

Adds the key with the provided value to the map, if the key was already part
of the collection, its value is overwritten.

### `delete(key: Object)`

Attempts to remove a key from the collection. The method will abort silently if
the key is not part of the collection.

### `has(key: Object): boolean`

Returns true if there is a value for the provided key in this collection.

### `get(key: Object): Object | undefined`

Retrieves the value of the provided key, or `undefined` if the key was not found.

{% include links.html %}
