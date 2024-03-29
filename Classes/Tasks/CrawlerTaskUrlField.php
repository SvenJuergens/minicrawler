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
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Scheduler\Task\Enumeration\Action;

/**
 * Original TASK taken from EXT:reports
 */
class CrawlerTaskUrlField extends AbstractAdditionalFieldProvider
{

    /**
     * Additional fields
     *
     * @var array
     */
    protected $fields = ['urlsToCrawl'];

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
     * @param AbstractTask $task The task object being edited. Null when adding a task!
     * @param SchedulerModuleController $schedulerModule Reference to the scheduler backend module
     * @return array A two dimensional array, array('Identifier' => array('fieldId' => array('code' => '', 'label' => '', 'cshKey' => '', 'cshLabel' => ''))
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule)
    {
        $fields = ['urlsToCrawl' => 'textarea'];
        $currentSchedulerModuleAction = $currentSchedulerModuleAction = $schedulerModule->getCurrentAction();
        if ($currentSchedulerModuleAction === Action::EDIT) {
            $taskInfo[$this->fieldPrefix . 'UrlsToCrawl'] = $task->getUrlsToCrawl();
        }
        // build html field for additional field
        $fieldName = $this->getFullFieldName('urlsToCrawl');
        $fieldId = 'task_' . $fieldName;
        $placeholderText = $GLOBALS['LANG']->sL('LLL:EXT:minicrawler/Resources/Private/Language/locallang.xlf:scheduler.placeholderText');
        $fieldHtml = '<textarea  class="form-control" rows="10" cols="75" placeholder="' . htmlspecialchars($placeholderText) . '" name="tx_scheduler[' . $fieldName . ']" ' . '>' . htmlspecialchars($taskInfo[$fieldName]) . '</textarea>';

        $additionalFields = [];
        $additionalFields[$fieldId] = [
            'code' => $fieldHtml,
            'label' => $GLOBALS['LANG']->sL('LLL:EXT:minicrawler/Resources/Private/Language/locallang.xlf:scheduler.fieldLabel'),
            'cshKey' => '',
            'cshLabel' => $fieldId
        ];

        return $additionalFields;
    }

    /**
     * Validates the additional fields' values
     *
     * @param array $submittedData An array containing the data submitted by the add/edit task form
     * @param SchedulerModuleController $schedulerModule Reference to the scheduler backend module
     * @return bool TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule)
    {
        $validInput = true;
        $urlsToCrawl = GeneralUtility::trimExplode(LF, $submittedData[$this->fieldPrefix . 'UrlsToCrawl'], true);
        foreach ($urlsToCrawl as $url) {
            if (!GeneralUtility::isValidUrl($url)) {
                $validInput = false;
                break;
            }
        }
        if (empty($submittedData[$this->fieldPrefix . 'UrlsToCrawl']) || !$validInput) {
            $message = htmlspecialchars($GLOBALS['LANG']->sL('LLL:EXT:minicrawler/Resources/Private/Language/locallang.xlf:scheduler.error.urlNotValid'));
            //@extensionScannerIgnoreLine
            $this->addMessage($message, FlashMessage::ERROR);
            $validInput = false;
        }
        return $validInput;
    }

    /**
     * Takes care of saving the additional fields' values in the task's object
     *
     * @param array $submittedData An array containing the data submitted by the add/edit task form
     * @param AbstractTask $task Reference to the scheduler backend module
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
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
    protected function getFullFieldName($fieldName): string
    {
        return $this->fieldPrefix . ucfirst($fieldName);
    }
}
