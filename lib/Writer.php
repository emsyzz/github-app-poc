<?php

class Writer
{
    private $f;

    private $messages = [];

    public function __construct() {
        $this->f = fopen('php://stdout', 'w');
    }

    public function __destruct() {
        $this->outputMessages();
        fclose($this->f);
    }

    private function outputMessages(): void
    {
        foreach ($this->messages as $message) {
            fwrite($this->f, $message . "\n");
        }
    }

    public function write(string $message): void
    {
        $this->messages[] = $message;
    }

    public function writeDump(string $label, $data): void
    {
        ob_start();
        var_dump($data);
        $this->write($label . PHP_EOL . ob_get_clean());
    }
}
