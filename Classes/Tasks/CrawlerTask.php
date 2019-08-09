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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class CrawlerTask extends AbstractTask
{

    /**
     * URLs to crawl
     *
     * @var string
     */
    protected $urlsToCrawl = null;

    public function execute()
    {
        $urls = GeneralUtility::trimExplode(LF, $this->urlsToCrawl, true);

        if (is_array($urls)) {
            foreach ($urls as $key => $url) {
                GeneralUtility::getUrl($url);
            }
        }
        return true;
    }

    /**
     * Gets the URLS to crawl.
     *
     * @return string URLS to crawl.
     */
    public function getUrlsToCrawl()
    {
        return $this->urlsToCrawl;
    }

    /**
     * Sets the URLS to crawl.
     *
     * @param string $urlsToCrawl URLS to crawl.
     */
    public function setUrlsToCrawl($urlsToCrawl)
    {
        $this->urlsToCrawl = $urlsToCrawl;
    }
}
