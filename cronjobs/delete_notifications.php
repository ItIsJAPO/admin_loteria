<?php

include '__init.php';

use cronjobs\jobs\DeleteNotifications;

try {

	$job = new DeleteNotifications();

	$job->run();

	echo "cron delete_notifications SUCCESS";

} catch( \Exception $e ) {
	echo "cron delete_notifications FAILED [" . $e->getMessage() . "]";

	Logger()->error($e);
}