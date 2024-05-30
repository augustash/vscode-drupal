<?php

namespace VscodeDrupal;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Ddev console class.
 */
class Install {

  /**
   * Path to gitignore file.
   *
   * @var string
   */
  private static $gitIgnorePath = __DIR__ . '/../../../../.gitignore';

  /**
   * The docroot.
   *
   * @var string
   */
  private static $docRoot;

  /**
   * Run on post-install-cmd.
   *
   * @param \Composer\Script\Event $event
   *   The event.
   */
  public static function postPackageInstall(Event $event) {
    $fileSystem = new Filesystem();
    $io = $event->getIO();

    try {
      $settingsPath = static::getWebRootPath() . '.vscode/settings.json';
      $settings = [];
      if ($fileSystem->exists($settingsPath)) {
        $jsonString = file_get_contents($settingsPath);
        $settings = json_decode($jsonString, TRUE);
      }
      $settings['workbench.colorCustomizations']['titleBar.activeForeground'] = '#f1f1f1';
      $settings['workbench.colorCustomizations']['titleBar.inactiveForeground'] = '#f1f1f1';
      $settings['workbench.colorCustomizations']['titleBar.activeBackground'] = '#f1f1f1';
      $settings['workbench.colorCustomizations']['titleBar.inactiveBackground'] = '#f1f1f1';
      $fileSystem->dumpFile($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
    }
    catch (\Error $e) {
      $io->error('<error>' . $e->getMessage() . '</error>');
    }

    // .gitignore.
    try {
      $gitignore = $fileSystem->exists(static::$gitIgnorePath) ? file_get_contents(static::$gitIgnorePath) : '';
      if (strpos($gitignore, '# Ignore ddev files') === FALSE) {
        $gitignore .= "\n" . file_get_contents(__DIR__ . '/../assets/.gitignore.append');
        $fileSystem->dumpFile(static::$gitIgnorePath, $gitignore);
      }
    }
    catch (\Error $e) {
      $io->error('<error>' . $e->getMessage() . '</error>');
    }
  }

  /**
   * Get docroot.
   */
  protected static function getWebRootPath() {
    $root = __DIR__ . '/../../../../';
    if ($docroot = static::$docRoot) {
      $root .= $docroot . '/';
    }
    return $root;
  }

}
