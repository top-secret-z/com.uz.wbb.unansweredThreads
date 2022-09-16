<?php
namespace wbb\system\page\handler;
use wbb\data\thread\UnansweredThread;
use wcf\system\page\handler\AbstractMenuPageHandler;
use wcf\system\WCF;

/**
 * Page handler for unanswered thread list.
 *
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wbb.unansweredThreads
 */
class UnansweredThreadListPageHandler extends AbstractMenuPageHandler {
	/**
	 * @inheritDoc
	 */
	public function isVisible($objectID = null) {
		return (WCF::getUser()->userID != 0 && WCF::getSession()->getPermission('user.board.canViewUnansweredThreads') && UnansweredThread::getUnansweredThreads());
	}
}
