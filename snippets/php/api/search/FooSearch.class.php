<?php

namespace wcf\system\search;

use wcf\data\foo\SearchResultFoo;
use wcf\data\foo\SearchResultFooList;
use wcf\data\search\ISearchResultObject;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Search provider for foo objects.
 */
class FooSearch extends AbstractSearchProvider
{
    /**
     * @var SearchResultFoo[]
     */
    private array $messageCache = [];

    #[\Override]
    public function cacheObjects(array $objectIDs, ?array $additionalData = null): void
    {
        $list = new SearchResultFooList();
        $list->setObjectIDs($objectIDs);
        $list->readObjects();
        foreach ($list->getObjects() as $foo) {
            $this->messageCache[$foo->fooID] = $foo;
        }
    }

    #[\Override]
    public function getObject(int $objectID): ?ISearchResultObject
    {
        return $this->messageCache[$objectID] ?? null;
    }

    #[\Override]
    public function getTableName(): string
    {
        return 'wcf1_foo';
    }

    #[\Override]
    public function getIDFieldName(): string
    {
        return $this->getTableName() . '.fooID';
    }

    #[\Override]
    public function getSubjectFieldName(): string
    {
        return $this->getTableName() . '.title';
    }

    #[\Override]
    public function getUsernameFieldName(): string
    {
        return $this->getTableName() . '.username';
    }

    #[\Override]
    public function getTimeFieldName(): string
    {
        return $this->getTableName() . '.time';
    }

    #[\Override]
    public function getConditionBuilder(array $parameters): ?PreparedStatementConditionBuilder
    {
        $conditionBuilder = new PreparedStatementConditionBuilder();

        // Only show foo objects that the current user can access.
        if (!WCF::getSession()->getPermission('mod.foo.canViewAll')) {
            $conditionBuilder->add('wcf1_foo.isPublished = ?', [1]);
        }

        return $conditionBuilder;
    }

    #[\Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->getPermission('user.foo.canSearch');
    }
}
