<?php

namespace cronjobs\jobs;

use repository\NotificationsDAO;
use repository\Notifications;

class DeleteNotifications {

	public function run() {
		$now = new \DateTime();
		$daoNotifications = new NotificationsDAO();

		$notifications = $daoNotifications->findByField(array(
			'fetchAll' => true,
			'fieldName' => 'status',
			'fieldValue' => Notifications::LEIDO
		));

		if ( !empty($notifications) ) {
			foreach ( $notifications as $notification ) {
				$readed = new \DateTime($notification->getReaded());

				$diff = $readed->diff($now);
				
				if ( intval($diff->days) > 30 ) {
					$daoNotifications->deleteById($notification->getId());
				}
			}
		}
	}
}