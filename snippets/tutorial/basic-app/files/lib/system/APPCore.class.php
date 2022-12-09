<?php

namespace app\system;

use app\page\ExamplePage;
use wcf\system\application\AbstractApplication;

final class APPCore extends AbstractApplication
{
    /**
     * @inheritDoc
     */
    protected $primaryController = ExamplePage::class;
}
