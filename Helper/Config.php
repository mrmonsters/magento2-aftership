<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 05/12/2017
 * Time: 9:38 AM
 */

namespace Mrmonsters\Aftership\Helper;

use Magento\Config\Model\Config\Factory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config extends AbstractHelper {

	const XML_PATH_MESSAGES_API_KEY         = 'aftership_options/messages/api_key';
	const XML_PATH_MESSAGES_STATUS          = 'aftership_options/messages/status';
	const XML_PATH_MESSAGES_CRON_JOB_ENABLE = 'aftership_options/messages/cron_job_enable';
	const XML_PATH_MESSAGES_LAST_UPDATE     = 'aftership_options/messages/last_update';

	protected $_configFactory;
	protected $_storeManager;

	/**
	 * @inheritDoc
	 */
	public function __construct(Factory $factory, StoreManagerInterface $storeManager, Context $context)
	{
		$this->_configFactory = $factory;
		$this->_storeManager  = $storeManager;

		parent::__construct($context);
	}

	public function getExtensionApiKey($websiteId = null)
	{
		return $this->scopeConfig->getValue(self::XML_PATH_MESSAGES_API_KEY, ScopeInterface::SCOPE_WEBSITES, $websiteId);
	}

	public function getExtensionEnabled($websiteId = null)
	{
		return $this->scopeConfig->getValue(self::XML_PATH_MESSAGES_STATUS, ScopeInterface::SCOPE_WEBSITES, $websiteId);
	}

	public function getExtensionCronJobEnabled($websiteId = null)
	{
		return $this->scopeConfig->getValue(self::XML_PATH_MESSAGES_CRON_JOB_ENABLE, ScopeInterface::SCOPE_WEBSITES, $websiteId);
	}

	public function getExtensionLastUpdate($websiteId = null)
	{
		$scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;

		if ($websiteId) {

			$scopeType = ScopeInterface::SCOPE_WEBSITES;
		}

		return $this->scopeConfig->getValue(self::XML_PATH_MESSAGES_LAST_UPDATE, $scopeType, $websiteId);
	}

	public function setExtensionCronLastUpdate($time)
	{
		$defaultConfig = $this->_configFactory->create([
			'data' => [
				'section' => 'aftership_options',
				'website' => null,
				'store'   => null,
				'groups'  => [
					'messages' => [
						'fields' => [
							'last_update' => [
								'value' => $time,
							],
						],
					],
				],
			],
		]);

		$defaultConfig->save();

		foreach ($this->_storeManager->getWebsites() as $website) {

			$config = $this->_configFactory->create([
				'data' => [
					'section' => 'aftership_options',
					'website' => $website->getId(),
					'store'   => null,
					'groups'  => [
						'messages' => [
							'fields' => [
								'last_update' => [
									'value' => $time,
								],
							],
						],
					],
				],
			]);

			$config->save();
		}
	}
}