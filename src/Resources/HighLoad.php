<?php

namespace App\Resources;

use App\Interfaces\ResourceInterface;

class HighLoad implements ResourceInterface
{

    public function getUrl(): string
    {
        return 'https://highload.today/category/novosti/';
    }

    public function getName(): string
    {
        return 'HighLoad';
    }

    public function getWrapperSelector(): string
    {
        return 'div.col.sidebar-center lenta-item';
    }

    public function getTitleSelector(): string
    {
        return 'a h2';
    }

    public function getDescSelector(): string
    {
        return 'p';
    }

    public function getDateSelector(): string
    {
        return 'span.meta-datetime';
    }

    public function getImageSelector(): string
    {
        return 'a div.lenta-image';
    }
}