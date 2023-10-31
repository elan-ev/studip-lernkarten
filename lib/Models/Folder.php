<?php

namespace Lernkarten\Models;

use Course;
use RuntimeException;
use SimpleORMap;
use User;

class Folder extends SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'lernkarten_folders';

        $config['belongs_to']['parent'] = [
            'class_name' => Folder::class,
            'foreign_key' => 'parent_id',
        ];

        parent::configure($config);
    }

    /**
     * @return User|Course|null
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getContext()
    {
        switch ($this->context_type) {
            case Course::class:
                return Course::find($this->context_id);
            case User::class:
                return User::find($this->context_id);
        }

        throw RuntimeException('Unknown context_type.');
    }
}
