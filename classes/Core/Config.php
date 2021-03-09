<?php

namespace Classes\Core;

class Config
{
    protected static ?Config $instance = null;
    protected array $fileContent;

    private function __construct(){}

    public static function getInstance(): self
    {
        if (self::$instance === null){
            self::$instance = new self();
            self::$instance->fileContent = require_once rootPath . '/config.php';
        }

        return self::$instance;
    }

    public function getByKey(string $key): ?string
    {
        $keyArray = explode('.', $key);
        $configContent = null;

        foreach ($keyArray as $key){
            if (!is_string($configContent)) {
                if ($configContent === null) {
                    $configContent = $this->fileContent[$key];
                } else {
                    $configContent = $configContent[$key];
                }
            }
        }
        return $configContent;
    }
}