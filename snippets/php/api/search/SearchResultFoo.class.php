<?php

namespace wcf\data\foo;

use wcf\data\DatabaseObjectDecorator;
use wcf\data\search\ISearchResultObject;
use wcf\data\user\UserProfile;
use wcf\system\request\LinkHandler;
use wcf\system\search\SearchResultTextParser;

/**
 * Represents a foo object as a search result.
 *
 * @mixin Foo
 * @extends DatabaseObjectDecorator<Foo>
 */
class SearchResultFoo extends DatabaseObjectDecorator implements ISearchResultObject
{
    protected static $baseClass = Foo::class;

    #[\Override]
    public function getUserProfile(): ?UserProfile
    {
        return $this->getDecoratedObject()->getUserProfile();
    }

    #[\Override]
    public function getSubject(): string
    {
        return $this->getDecoratedObject()->getTitle();
    }

    #[\Override]
    public function getTime(): int
    {
        return $this->getDecoratedObject()->time;
    }

    #[\Override]
    public function getLink($query = ''): string
    {
        $parameters = [
            'object' => $this->getDecoratedObject(),
            'forceFrontend' => true,
        ];

        if ($query) {
            $parameters['highlight'] = \urlencode($query);
        }

        return LinkHandler::getInstance()->getLink('Foo', $parameters);
    }

    #[\Override]
    public function getObjectTypeName(): string
    {
        return 'com.example.foo';
    }

    #[\Override]
    public function getFormattedMessage(): string
    {
        return SearchResultTextParser::getInstance()->parse(
            $this->getDecoratedObject()->getMessage()
        );
    }

    #[\Override]
    public function getContainerTitle(): string
    {
        return '';
    }

    #[\Override]
    public function getContainerLink(): string
    {
        return '';
    }
}
