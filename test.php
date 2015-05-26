<?php

use \Semaphore\Semaphore;

require_once 'Semaphore.php';

$semaphore = new Semaphore();

if ($semaphore->lock(true)) {

    echo 'Doing things';

    fgetc(STDIN);
    
    echo 'Ready';
    $semaphore->unlock();

} else {
    echo 'could not start';
}