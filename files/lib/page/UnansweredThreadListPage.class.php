<?php
namespace wbb\page;
use wbb\data\thread\UnansweredThreadList;
use wcf\page\SortablePage;
use wcf\system\WCF;

/**
 * Shows the list of threads that contain no replies.
 *
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wbb.unansweredThreads
 */
class UnansweredThreadListPage extends SortablePage {
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
	public function readParameters() {
		parent::readParameters();
		
		if (WCF::getUser()->threadsPerPage) {
			$this->itemsPerPage = WCF::getUser()->threadsPerPage;
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'validSortFields' => $this->validSortFields
		]);
	}
}
