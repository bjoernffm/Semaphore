<?php

namespace Semaphore;

class Semaphore
{
    private $handle;

    public function __construct()
    {
        if (is_writable($this->getFilename() . '.test'))
            throw new \Exception('Lockfile "' . $this->getFilename() . '" is not writable');
    }

    public function lock($waitUntilUnlocked = false)
    {
        $this->handle = fopen($this->getFilename(), "c+");
        var_dump($waitUntilUnlocked);
        $operation = LOCK_EX;
        
        if (!$waitUntilUnlocked)
            $operation |= LOCK_NB;

        if (!flock($this->handle, $operation))
            return false;

        return true;
    }

    public function unlock()
    {
        flock($this->handle, LOCK_UN);
        fclose($this->handle);
        //unlink($this->getFilename());
    }

    public function __destruct()
    {
        //$this->unlock();
    }

    private function getFilename()
    {
        $fn = sha1($_SERVER['SCRIPT_NAME']) . '.lock';
        $subDir = substr($fn, 0, 2);
        
        if (!is_dir(__DIR__ . '/locks/' . $subDir))
            mkdir(__DIR__ . '/locks/' . $subDir);

        return __DIR__ . '/locks/' . $subDir . '/' . $fn;
    }
}
