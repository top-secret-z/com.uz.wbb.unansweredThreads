<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wbb\data\thread;

use wbb\data\board\Board;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\language\LanguageFactory;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;

/**
 * Represents an unanswered thread.
 */
class UnansweredThread extends DatabaseObjectDecorator
{
    /**
     * @inheritDoc
     */
    protected static $baseClass = Thread::class;

    /**
     * number of unanswered threads
     */
    protected static $unansweredThreads;

    /**
     * Returns the number of unanswered threads.
     */
    public static function getUnansweredThreads()
    {
        if (self::$unansweredThreads === null) {
            self::$unansweredThreads = 0;

            if (WCF::getUser()->userID) {
                // remove ignored and excluded boards
                $boardIDs = Board::getAccessibleBoardIDs();
                if (empty($boardIDs)) {
                    return 0;
                }

                if (WBB_MODULE_IGNORE_BOARDS) {
                    $ignoredBoardIDs = UserCollapsibleContentHandler::getInstance()->getCollapsedContent(UserCollapsibleContentHandler::getInstance()->getObjectTypeID('com.woltlab.wbb.ignoredBoard'));
                    $boardIDs = \array_diff($boardIDs, $ignoredBoardIDs);
                    if (empty($boardIDs)) {
                        return 0;
                    }
                }

                if (WBB_BOARD_EXCLUDE_BOARD_IDS) {
                    $excludeBoardIDs = \explode("\n", WBB_BOARD_EXCLUDE_BOARD_IDS);
                    $boardIDs = \array_diff($boardIDs, $excludeBoardIDs);
                    if (empty($boardIDs)) {
                        return 0;
                    }
                }

                $conditionBuilder = new PreparedStatementConditionBuilder();
                $conditionBuilder->add("thread.boardID IN (?)", [$boardIDs]);
                $conditionBuilder->add("thread.isDeleted = ? AND thread.isDisabled = ? AND thread.movedThreadID IS NULL", [0, 0]);

                // apply period for unanswered threads
                $conditionBuilder->add("thread.replies = ?", [0]);
                if (WBB_BOARD_THREAD_UNANSWERED_PERIOD) {
                    $conditionBuilder->add("thread.lastPostTime > ?", [TIME_NOW - 86400 * WBB_BOARD_THREAD_UNANSWERED_PERIOD]);
                }

                // apply language filter
                if (LanguageFactory::getInstance()->multilingualismEnabled() && \count(WCF::getUser()->getLanguageIDs())) {
                    $conditionBuilder->add('(thread.languageID IN (?) OR thread.languageID IS NULL)', [WCF::getUser()->getLanguageIDs()]);
                }

                $sql = "SELECT    COUNT(*) AS count
                        FROM    wbb" . WCF_N . "_thread thread
                        " . $conditionBuilder;
                $statement = WCF::getDB()->prepareStatement($sql);
                $statement->execute($conditionBuilder->getParameters());
                self::$unansweredThreads = $statement->fetchSingleColumn();
            }
        }

        return self::$unansweredThreads;
    }
}
