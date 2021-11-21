<?php

namespace App\Models\Journal;

class ContentType
{
    public const NEWS = 1;
    public const ARTICLE = 2;
    public const EVENT = 3;

    public static function getAll(): array
    {
        return [
            self::NEWS => 'Новость',
            self::ARTICLE => 'Статья',
            self::EVENT => 'Мероприятие',
        ];
    }

    public static function byId($type): string
    {
        return self::getAll()[$type];
    }
}
