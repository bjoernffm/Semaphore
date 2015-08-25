# Semaphore

Semaphore is an implementaion of a binary semaphore (e.g. prevention of race conditions) for PHP 5.3+

## Features

- try/lock in two differen variations
  - return bool if locking was successful
  - wait until locking is successful
- unlocking

## Example

```php
<?php

use \Semaphore\Semaphore;
require_once 'Semaphore.php';
$semaphore = new Semaphore();

// The script will pause here until semaphore is unlocked
if ($semaphore->lock(true)) {
    echo 'Doing things';
    $semaphore->unlock();
} else {
    echo 'could not start';
}
```

## Class methods
#### `bool lock(bool $waitUntilUnlocked = false)`
Try to lock the semaphore if possible. Returns if locking was successful. Waits until possible if $waitUntilUnlocked == true.
#### `void unlock()`
Unlocks the semaphore.


