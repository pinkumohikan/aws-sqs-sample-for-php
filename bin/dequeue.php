<?php

require_once dirname(__DIR__).'/bootstrap.php';

$queueName = 'maji-de-sugoi-queue';

$queueHandler = new App\Queue\Handler($queueName);

var_dump($queueHandler->pop(2));
