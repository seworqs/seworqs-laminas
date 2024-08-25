<?php

declare(strict_types=1);

namespace Seworqs\Laminas\I18n\Translator;

use Laminas\I18n\Translator\Translator as LaminasTranslator;

class Translator extends LaminasTranslator
{
    private $modules;
    public function __construct()
    {
        $this->reloadTranslations();
    }

    public function reloadTranslations()
    {

        $moduleIterator = new \DirectoryIterator('./module');
        foreach ($moduleIterator as $modulePath) {
            if ($modulePath->isDir() && ! $modulePath->isDot()) {
                $moduleName = $modulePath->getFileName();
                $languageDirPath = sprintf('./module/%s/languages', $moduleName);
                if (is_dir($languageDirPath)) {
                    $dirIterator = new \DirectoryIterator($languageDirPath);
                    foreach ($dirIterator as $languagePath) {
                        if ($languagePath->isDir() && ! $languagePath->isDot()) {
                            $locale = $languagePath->getFileName();
                            $messagesPath = sprintf('%s/%s/LC_MESSAGES', $languageDirPath, $locale);
                            if (is_dir($messagesPath)) {
                                $moFiles = glob($messagesPath . '/*.mo');
                                if (! empty($moFiles)) {
                                    foreach ($moFiles as $moFile) {
                                        $this->addTranslationFile('gettext', $moFile, 'default', $locale);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
