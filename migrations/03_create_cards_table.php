<?php

class CreateCardsTable extends Migration
{
    public function description()
    {
        return 'Creates the cards table in the database.';
    }

    public function up()
    {
        $dbm = \DBManager::get();
        $dbm->exec("
            CREATE TABLE `lernkarten_cards` (
              `id` int(11) UNSIGNED NOT NULL,
              `note_id` int(11) UNSIGNED NOT NULL,
              `deck_id` int(11) UNSIGNED NOT NULL,
              `mkdate` int(11) NOT NULL,
              `chdate` int(11) NOT NULL
            )");
        $dbm->exec("
            ALTER TABLE `lernkarten_cards`
              ADD PRIMARY KEY (`id`),
              ADD KEY `index_note_id` (`note_id`),
              ADD KEY `index_deck_id` (`deck_id`)");
    }

    public function down()
    {
        $dbm = \DBManager::get();
        $dbm->exec('DROP TABLE IF EXISTS `lernkarten_cards`');
    }
}
