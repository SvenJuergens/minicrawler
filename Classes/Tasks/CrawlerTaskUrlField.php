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

use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

use SvenJuergens\Minicrawler\Tasks\CrawlerTask;

/**
 * Original TASK taken from EXT:reports
 *
 */
class CrawlerTaskUrlField implements AdditionalFieldProviderInterface{

	/**
	 * Additional fields
	 *
	 * @var array
	 */
	protected $fields = array('urlsToCrawl');

	/**
	 * Field prefix.
	 *
	 * @var string
	 */
	protected $fieldPrefix = 'miniCrawler';

	/**
	 * Gets additional fields to render in the form to add/edit a task
	 *
	 * @param array $taskInfo Values of the fields from the add/edit task form
	 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task The task object being edited. Null when adding a task!
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the scheduler backend module
	 * @return array A two dimensional array, array('Identifier' => array('fieldId' => array('code' => '', 'label' => '', 'cshKey' => '', 'cshLabel' => ''))
	 */
	public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule) {
		$fields = array('urlsToCrawl' => 'textarea');
		if ($schedulerModule->CMD == 'edit') {
			$taskInfo[$this->fieldPrefix . 'UrlsToCrawl'] = $task->getUrlsToCrawl();
		}
		// build html field for additional field
		$fieldName = $this->getFullFieldName('urlsToCrawl');
		$fieldId = 'task_' . $fieldName;
		$placeholderText = $GLOBALS['LANG']->sL('LLL:EXT:minicrawler/locallang.xml:scheduler.placeholderText');
		$fieldHtml = '<textarea  class="form-control" rows="10" cols="75" placeholder="' . htmlspecialchars($placeholderText) . '" name="tx_scheduler[' . $fieldName . ']" ' . '>' . htmlspecialchars($taskInfo[$fieldName]) . '</textarea>';

		$additionalFields = array();
		$additionalFields[$fieldId] = array(
			'code' => $fieldHtml,
			'label' => $GLOBALS['LANG']->sL('LLL:EXT:minicrawler/locallang.xml:scheduler.fieldLabel'),
			'cshKey' => '',
			'cshLabel' => $fieldId
		);

		return $additionalFields;
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param array $submittedData An array containing the data submitted by the add/edit task form
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the scheduler backend module
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule) {
		$validInput = TRUE;
		$urlsToCrawl = GeneralUtility::trimExplode(LF, $submittedData[$this->fieldPrefix . 'UrlsToCrawl'], TRUE);
		foreach ($urlsToCrawl as $url) {
			if ( !GeneralUtility::isValidUrl($url) ) {
				$validInput = FALSE;
				break;
			}
		}
		if (empty($submittedData[$this->fieldPrefix . 'UrlsToCrawl']) || !$validInput) {
			$message = htmlspecialchars( $GLOBALS['LANG']->sL('LLL:EXT:minicrawler/locallang.xml:scheduler.error.urlNotValid') );
			$schedulerModule->addMessage($message, FlashMessage::ERROR);
			$validInput = FALSE;
		}
		return $validInput;
	}

	/**
	 * Takes care of saving the additional fields' values in the task's object
	 *
	 * @param array $submittedData An array containing the data submitted by the add/edit task form
	 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the scheduler backend module
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, AbstractTask $task) {
		if (!$task instanceof CrawlerTask) {
			throw new \InvalidArgumentException('Expected a task of type SvenJuergens\\Minicrawler\\Tasks\\CrawlerTask, but got ' . get_class($task), 1295012802);
		}
		$task->setUrlsToCrawl($submittedData[$this->fieldPrefix . 'UrlsToCrawl']);
	}

	/**
	 * Constructs the full field name which can be used in HTML markup.
	 *
	 * @param string $fieldName A raw field name
	 * @return string Field name ready to use in HTML markup
	 */
	protected function getFullFieldName($fieldName) {
		return $this->fieldPrefix . ucfirst($fieldName);
	}

}