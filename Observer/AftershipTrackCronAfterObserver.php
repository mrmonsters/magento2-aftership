<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 05/12/2017
 * Time: 12:46 PM
 */

namespace Mrmonsters\Aftership\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mrmonsters\Aftership\Helper\Config;

class AftershipTrackCronAfterObserver implements ObserverInterface {

	protected $_configHelper;

	/**
	 * AftershipTrackCronAfterObserver constructor.
	 *
	 * @param \Mrmonsters\Aftership\Helper\Config $config
	 */
	public function __construct(Config $config)
	{
		$this->_configHelper = $config;
	}

	/**
	 * @inheritDoc
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$time = $observer->getData('time');

		$this->_configHelper->setExtensionCronLastUpdate($time);
	}

}