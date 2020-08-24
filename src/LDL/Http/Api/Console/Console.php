<?php

namespace LDL\Http\Api\Console;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Util\Path;
use Symfony\Component\Console\Application as SymfonyApplication;

class Console extends SymfonyApplication
{
    const BANNER = <<<'BANNER'
BANNER;

    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        echo self::BANNER;
        parent::__construct('<info>[ API project ]</info>', $version);

        $commands = LocalFileFinder::findRegex(
            '^.*\.php$',
            [
                Path::make(__DIR__, 'Command')
            ]
        );

        $commands = array_map(function($item) {
            return $item->getRealPath();
        },\iterator_to_array($commands));

        usort($commands, function($a, $b){
            return strcmp($a, $b);
        });

        /**
         * @var \SplFileInfo $commandFile
         */
        foreach($commands as $key => $commandFile){
            require $commandFile;

            $class = get_declared_classes();
            $class = $class[count($class) - 1];

            $this->add(new $class());
        }
    }
}
