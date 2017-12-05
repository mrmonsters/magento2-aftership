<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 4:23 PM
 */

namespace Mrmonsters\Aftership\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Shipping\Model\Order\Track;
use Mrmonsters\Aftership\Helper\Config;
use Mrmonsters\Aftership\Helper\Data;
use Mrmonsters\Aftership\Model\ResourceModel\Track\CollectionFactory;

class ShipmentTrackSaveAfterObserver implements ObserverInterface {

	protected $_trackCollectionFactory;
	protected $_aftershipHelper;
	protected $_configHelper;

	/**
	 * ShipmentTrackSaveAfterObserver constructor.
	 *
	 * @param \Mrmonsters\Aftership\Model\ResourceModel\Track\CollectionFactory $trackCollectionFactory
	 * @param \Mrmonsters\Aftership\Helper\Data                                 $aftershipHelper
	 * @param \Mrmonsters\Aftership\Helper\Config                               $configHelper
	 */
	public function __construct(CollectionFactory $trackCollectionFactory, Data $aftershipHelper, Config $configHelper)
	{
		$this->_trackCollectionFactory = $trackCollectionFactory;
		$this->_aftershipHelper        = $aftershipHelper;
		$this->_configHelper           = $configHelper;
	}

	/**
	 * @inheritDoc
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		ob_start();

		/* @var Track $magentoTrack */
		$magentoTrack = $observer->getEvent()->getTrack();
		/* @var Order $magentoOrder */
		$magentoOrder = $magentoTrack->getShipment()->getOrder();
		$enabled      = $this->_configHelper->getExtensionEnabled($magentoOrder->getStore()->getWebsiteId());
		$tracks       = $this->_trackCollectionFactory->create()
		                                              ->addFieldToFilter('tracking_number', ['eq' => $magentoTrack->getTrackNumber()])
		                                              ->addFieldToFilter('order_id', ['eq' => $magentoOrder->getIncrementId()])
		                                              ->getItems();

		if (empty($tracks)) {

			$track = $this->_aftershipHelper->saveTrack($magentoTrack);
		} else {

			$track = reset($tracks);
		}

		if ($enabled) {

			$this->_aftershipHelper->sendTrack($track);
		}

		ob_end_clean();
	}

}