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
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;

/**
 * Represents a list of threads that are unanswered.
 */
class UnansweredThreadList extends AccessibleThreadList
{
    /**
     * Creates a new UnansweredThreadList object.
     */
    public function __construct()
    {
        parent::__construct();

        // boards
        $boardIDs = Board::getAccessibleBoardIDs();
        if (empty($boardIDs)) {
            $this->getConditionBuilder()->add('1=0');
        } else {
            // ignored and excludes boards
            $ignoredBoardIDs = $excludeBoardIDs = [];

            if (WBB_MODULE_IGNORE_BOARDS) {
                $ignoredBoardIDs = UserCollapsibleContentHandler::getInstance()->getCollapsedContent(UserCollapsibleContentHandler::getInstance()->getObjectTypeID('com.woltlab.wbb.ignoredBoard'));
            }
            if (WBB_BOARD_EXCLUDE_BOARD_IDS) {
                $excludeBoardIDs = \explode("\n", WBB_BOARD_EXCLUDE_BOARD_IDS);
            }

            $boardIDs = \array_diff($boardIDs, $ignoredBoardIDs, $excludeBoardIDs);
            if (empty($boardIDs)) {
                $this->getConditionBuilder()->add('1=0');
            } else {
                $this->getConditionBuilder()->add("thread.boardID IN (?)", [$boardIDs]);

                // closed and done
                if (!WBB_BOARD_THREAD_UNANSWERED_CLOSED) {
                    $this->getConditionBuilder()->add("thread.isClosed = ?", [0]);
                }
                if (!WBB_BOARD_THREAD_UNANSWERED_DONE) {
                    $this->getConditionBuilder()->add("thread.isDone = ?", [0]);
                }

                // period
                $this->getConditionBuilder()->add("thread.replies = ?", [0]);
                if (WBB_BOARD_THREAD_UNANSWERED_PERIOD) {
                    $this->getConditionBuilder()->add("thread.lastPostTime >= ?", [TIME_NOW - 86400 * WBB_BOARD_THREAD_UNANSWERED_PERIOD]);
                }
            }
        }
    }
}
