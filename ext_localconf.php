<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}	

/**
 * Registering class to scheduler
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['SvenJuergens\\Minicrawler\\Tasks\\CrawlerTask'] = array(
	'extension' => $_EXTKEY,
	'title' => 'Mini Crawler',
	'description' => 'Ruft die hitnerlegten Seiten auf, um den Cache zu generieren',
	'additionalFields' => 'SvenJuergens\\Minicrawler\\Tasks\\CrawlerTaskUrlField'

);