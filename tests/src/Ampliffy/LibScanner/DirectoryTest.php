<?php

use PHPUnit\Framework\TestCase;
use Ampliffy\LibScanner\Util\Directory;

class DirectoryTest extends TestCase
{

    public function testValidDirectories():void
    {
        $dirs = [
            '/home/pablo/workspace/ampliffy/lib-scanner',
            '/home/pablo/workspace/ampliffy/test/lib-scanner'
        ];

        foreach ($dirs as $dir) {
            $this->assertTrue(Directory::isValid($dir));
        }
    }

    public function testNotValidDirectory():void
    {
        $this->expectException(\Exception::class);

        Directory::isValid('/var/dir/not/valid');
    }

    public function testDirectoryWithParams():void
    {
        $this->expectException(\Exception::class);

        Directory::isValid('/home/pablo/workspace/ampliffy/lib-scanner && ls');
    }
}
