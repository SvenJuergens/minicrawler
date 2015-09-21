<?php
namespace SvenJuergens\Minicrawler\Tasks;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CrawlerTask extends AbstractTask {

	/**
	 * URLs to crawl
	 *
	 * @var string
	 */
	protected $urlsToCrawl = NULL;


	public function execute() {
		$urls = GeneralUtility::trimExplode(LF, $this->urlsToCrawl, TRUE);

		if(is_array($urls)){
			foreach ($urls as $key => $url) {
				GeneralUtility::getUrl($url);
			}
		}
		return TRUE;
	}

	/**
	 * Gets the URLS to crawl.
	 *
	 * @return string URLS to crawl.
	 */
	public function getUrlsToCrawl() {
		return $this->urlsToCrawl;
	}

	/**
	 * Sets the URLS to crawl.
	 *
	 * @param string $urlsToCrawl URLS to crawl.
	 * @return void
	 */
	public function setUrlsToCrawl($urlsToCrawl) {
		$this->urlsToCrawl = $urlsToCrawl;
	}

}
