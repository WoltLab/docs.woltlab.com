<?php

namespace wcf\data\foo;

/**
 * Represents a list of foo objects as search results.
 *
 * @extends FooList<SearchResultFoo>
 */
class SearchResultFooList extends FooList
{
    public $decoratorClassName = SearchResultFoo::class;
}
