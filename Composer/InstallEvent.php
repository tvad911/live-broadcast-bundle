<?php

namespace Martin1982\LiveBroadcastBundle\Composer;

use Composer\Downloader\ZipDownloader;
use Composer\Package\Package;
use Composer\Script\Event;
use Composer\Util\ProcessExecutor;
use Composer\Util\Filesystem;

/**
 * Class InstallEvent
 * @package Martin1982\LiveBroadcastBundle\Composer
 */
class InstallEvent
{
    const PACKAGE_NAME = 'martin1982/live-broadcast-bundle';
    const RED5_DEFAULT_INSTALL_PATH = 'lib/red5';
    const RED5_RELEASE_REQUIREMENT = 'v1.0.7-RELEASE';
    const RED5_RELEASE_ZIP_URL = 'https://github.com/Red5/red5-server/releases/download/v1.0.7-RELEASE/red5-server-1.0.7-RELEASE.zip';

    /** @var Event $event */
    private static $event;

    /** @var  string $installDirectory */
    private static $installDirectory;

    /**
     * @param Event $event
     */
    public static function postInstall(Event $event)
    {
        self::installRedFive($event);
    }

    /**
     * @param Event $event
     */
    public static function postUpdate(Event $event)
    {
        self::installRedFive($event);
    }

    /**
     * Ensure that a valid Red5 installation is present
     *
     * @param Event $event
     */
    private static function installRedFive(Event $event)
    {
        self::$event = $event;

        $composer = $event->getComposer();
        $inputOutput = $event->getIO();
        $packageName = self::PACKAGE_NAME;

        $inputOutput->write(sprintf('%s requires Red5 for RTMP input', $packageName));
        $inputOutput->write('Checking current Red 5 installation');

        $vendorDir = $composer->getConfig()->get('vendor-dir');
        $installDirectory = $vendorDir . DIRECTORY_SEPARATOR . $packageName . DIRECTORY_SEPARATOR . self::RED5_DEFAULT_INSTALL_PATH;
        self::$installDirectory = $installDirectory;

        $installationStat = false;
        $versionStat = false;

        try {
            $installationStat = stat($installDirectory);
            $versionStat = stat($installDirectory . DIRECTORY_SEPARATOR . self::RED5_RELEASE_REQUIREMENT);
        } catch (\ErrorException $exception) {

        }

        if (!$installationStat) {
            $inputOutput->write('Red 5 installation not found, starting installation');

            return self::redInstall();
        }

        if (!$versionStat) {
            $inputOutput->write('Red 5 installation outdated, updating');

            return self::redUpdate();
        }

        return $inputOutput->write('Red 5 installation up to date, no action required');
    }

    /**
     * Install Red5 Server
     */
    private static function redInstall()
    {
        $composer = self::$event->getComposer();
        $inputOutput = self::$event->getIO();
        $versionDirectory = self::$installDirectory . DIRECTORY_SEPARATOR . self::RED5_RELEASE_REQUIREMENT;

        $inputOutput->write('Downloading Red5 Server...');
        $zipDownloader = new ZipDownloader($inputOutput, $composer->getConfig());

        $redPackage = new Package('Red5', self::RED5_RELEASE_REQUIREMENT, self::RED5_RELEASE_REQUIREMENT);
        $redPackage->setDistUrl(self::RED5_RELEASE_ZIP_URL);

        $zipDownloader->download($redPackage, $versionDirectory);
    }

    /**
     * Update Red5 Server
     */
    private static function redUpdate()
    {
        $inputOutput = self::$event->getIO();
        $executor = new ProcessExecutor($inputOutput);
        $filesystem = new Filesystem($executor);

        $inputOutput->write('Removing outdated versions');
        $filesystem->emptyDirectory(self::$installDirectory);

        self::redInstall();
    }
}