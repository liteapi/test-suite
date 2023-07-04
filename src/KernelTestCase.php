<?php

declare(strict_types=1);

namespace LiteApi\TestPack;

use Exception;
use LiteApi\Component\Config\ConfigLoader;
use LiteApi\Component\Config\Env;
use LiteApi\Kernel;
use PHPUnit\Framework\TestCase;

class KernelTestCase extends TestCase
{

    protected static ?Kernel $kernel;
    protected static bool $isBooted = false;

    /**
     * Boot kernel
     *
     * @return Kernel
     * @throws Exception
     */
    protected static function bootKernel(): Kernel
    {
        try {
            $projectDir = Env::getValue('APP_PROJECT_DIR');
        } catch (Exception $e) {
            throw new Exception('Cannot get value: APP_PROJECT_DIR from env. You must set value before testing', 0, $e);
        }
        $configLoader = new ConfigLoader($projectDir);
        $config = $configLoader->getConfig();
        self::$kernel = new Kernel($config);
        self::$kernel->boot();
        self::$isBooted = true;
        return self::$kernel;
    }

    protected function shutdownKernel(): void
    {
        if (self::$kernel === null) {
            return;
        }
        self::$kernel = null;
        self::$isBooted = false;
    }

}