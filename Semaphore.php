<?php

namespace Semaphore;

use \Exception;

class Semaphore
{
    private $handle;

    /**
     * Lock method
     *
     * This method is for locking the semaphore. If already locked, this method
     * returns false. An optional way is to wait until the semaphore is being
     * unlocked from another process.
     * 
     * @param  boolean $waitUntilUnlocked False by default. Waits until unlocked if true 
     * @return boolean Returns true if locking was successful, else false
     * @throws Exception If lock file could not be created
     */
    public function lock($waitUntilUnlocked = false)
    {
        $this->handle = @fopen($this->getFilename(), "c+");
        if ($this->handle === false)
            throw new Exception('Lockfile "' . $this->getFilename() . '" is not writable');

        $operation = LOCK_EX;
        
        if (!$waitUntilUnlocked)
            $operation |= LOCK_NB;

        if (!flock($this->handle, $operation))
            return false;

        return true;
    }

    /**
     * Unlock method
     *
     * This method unlocks the semaphore.
     */
    public function unlock()
    {
        @flock($this->handle, LOCK_UN);
        @fclose($this->handle);
    }

    /**
     * Destructor method
     */
    public function __destruct()
    {
        $this->unlock();
    }

    private function getFilename()
    {
        $fn = sha1($_SERVER['SCRIPT_NAME']) . '.lock';
        $subDir = substr($fn, 0, 2);

        if (!is_dir(__DIR__ . '/locks/' . $subDir))
            mkdir(__DIR__ . '/locks/' . $subDir, 0777, true);

        return __DIR__ . '/locks/' . $subDir . '/' . $fn;
    }
}
