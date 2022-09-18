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
namespace wbb\page;

use wbb\data\thread\UnansweredThreadList;
use wcf\page\SortablePage;
use wcf\system\WCF;

/**
 * Shows the list of threads that contain no replies.
 */
class UnansweredThreadListPage extends SortablePage
{
    /**
     * @inheritDoc
     */
    public $defaultSortField = WBB_BOARD_DEFAULT_SORT_FIELD;

    /**
     * @inheritDoc
     */
    public $defaultSortOrder = WBB_BOARD_DEFAULT_SORT_ORDER;

    /**
     * @inheritDoc
     */
    public $itemsPerPage = WBB_BOARD_THREADS_PER_PAGE;

    /**
     * @inheritDoc
     */
    public $objectListClassName = UnansweredThreadList::class;

    /**
     * @inheritDoc
     */
    public $validSortFields = ['topic', 'username', 'time', 'views', 'replies', 'lastPostTime', 'cumulativeLikes'];

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (WCF::getUser()->threadsPerPage) {
            $this->itemsPerPage = WCF::getUser()->threadsPerPage;
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'validSortFields' => $this->validSortFields,
        ]);
    }
}
