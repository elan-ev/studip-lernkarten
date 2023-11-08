<?php

namespace Lernkarten\Models;

use SimpleORMap;

class Note extends SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'lernkarten_notes';

        $config['has_many']['cards'] = [
            'class_name' => Card::class,
            'assoc_foreign_key' => 'note_id',
            'on_delete' => 'delete',
            'on_store' => 'store',
            'order_by' => 'ORDER BY mkdate',
        ];

        $config['registered_callbacks']['before_create'][] = function ($note) {
            do {
                $guid = sha1(uniqid(__CLASS__, true));
            } while (self::exists($guid));

            $note->guid = $guid;
        };

        parent::configure($config);
    }
}
