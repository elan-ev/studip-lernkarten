<?php

class CreateDecksTable extends Migration
{
    public function description()
    {
        return 'Creates the decks table in the database.';
    }

    public function up()
    {
        $dbm = \DBManager::get();
        $dbm->exec("
            CREATE TABLE `lernkarten_decks` (
              `id` int(11) UNSIGNED NOT NULL,
              `name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
              `description` text COLLATE utf8mb4_unicode_ci,
              `owner_id` char(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
              `mkdate` int(11) NOT NULL,
              `chdate` int(11) NOT NULL
            )");
        $dbm->exec("
            ALTER TABLE `lernkarten_decks`
              ADD PRIMARY KEY (`id`),
              ADD KEY `index_owner_id` (`owner_id`)");
    }

    public function down()
    {
        $dbm = \DBManager::get();
        $dbm->exec('DROP TABLE IF EXISTS `lernkarten_decks`');
    }
}
