<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Registering class to scheduler
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['SvenJuergens\\Minicrawler\\Tasks\\CrawlerTask'] = [
    'extension' => 'minicrawler',
    'title' => 'Mini Crawler',
    'description' => 'LLL:EXT:minicrawler/Resources/Private/Language/locallang.xlf:scheduler.description',
    'additionalFields' => 'SvenJuergens\\Minicrawler\\Tasks\\CrawlerTaskUrlField'
];
