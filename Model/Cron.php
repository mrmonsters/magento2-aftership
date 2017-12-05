<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 6:51 PM
 */

namespace Mrmonsters\Aftership\Model;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory;
use Mrmonsters\Aftership\Helper\Config;
use Mrmonsters\Aftership\Helper\Data;
use Mrmonsters\Aftership\Model\ResourceModel\Track\CollectionFactory as AsTrackCollectionFactory;

class Cron {

	protected $_scopeConfig;
	protected $_trackCollectionFactory;
	protected $_orderFactory;
	protected $_aftershipHelper;
	protected $_configHelper;
	protected $_asTrackCollectionFactory;
	protected $_configInterface;
	protected $_eventManager;

	/**
	 * Cron constructor.
	 *
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface                  $scopeConfig
	 * @param \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $collectionFactory
	 * @param \Magento\Sales\Model\OrderFactory                                   $orderFactory
	 * @param \Mrmonsters\Aftership\Helper\Data                                   $aftershipHelper
	 * @param \Mrmonsters\Aftership\Helper\Config                                 $configHelper
	 * @param \Mrmonsters\Aftership\Model\ResourceModel\Track\CollectionFactory   $asTrackCollectionFactory
	 * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface        $configInterface
	 *
	 */
	public function __construct(
		ScopeConfigInterface $scopeConfig,
		CollectionFactory $collectionFactory,
		OrderFactory $orderFactory,
		Data $aftershipHelper,
		Config $configHelper,
		AsTrackCollectionFactory $asTrackCollectionFactory,
		ConfigInterface $configInterface,
		ManagerInterface $manager
	)
	{
		$this->_scopeConfig              = $scopeConfig;
		$this->_trackCollectionFactory   = $collectionFactory;
		$this->_orderFactory             = $orderFactory;
		$this->_aftershipHelper          = $aftershipHelper;
		$this->_configHelper             = $configHelper;
		$this->_asTrackCollectionFactory = $asTrackCollectionFactory;
		$this->_configInterface          = $configInterface;
		$this->_eventManager             = $manager;
	}

	public function cron()
	{
		set_time_limit(0);

		$lastUpdate = $this->_configHelper->getExtensionLastUpdate();
		$debugRange = 1;

		if ($lastUpdate == '0' || !$lastUpdate) {

			$from = gmdate('Y-m-d H:i:s', time() - 3 * 60 * 60 * $debugRange); //past 3 hours
			$to   = gmdate('Y-m-d H:i:s');
		} else {

			$from = gmdate('Y-m-d H:i:s', $lastUpdate);
			$to   = gmdate('Y-m-d H:i:s');
		}

		$trackCollection = $this->_trackCollectionFactory->create()
		                                                 ->addAttributeToFilter('created_at', [
			                                                 'from' => $from,
			                                                 'to'   => $to,
		                                                 ])
		                                                 ->addAttributeToSort('created_at', 'asc');

		/* @var \Magento\Shipping\Model\Order\Track $magentoTrack */
		foreach ($trackCollection as $magentoTrack) {

			/* @var Order $order */
			$order       = $this->_orderFactory->create()->loadByIncrementId($magentoTrack->getOrderId());
			$enabled     = $this->_configHelper->getExtensionEnabled($order->getStore()->getWebsiteId());
			$cronEnabled = $this->_configHelper->getExtensionCronJobEnabled($order->getStore()->getWebsiteId());

			if ($enabled && $cronEnabled) {

				$tracks = $this->_asTrackCollectionFactory->create()
				                                          ->addFieldToFilter('tracking_number', array('eq' => $magentoTrack->getTrackNumber()))
				                                          ->getItems();
				$isSent = false;

				if (empty($tracks)) {

					// for the case that salesOrderShipmentTrackSaveAfter() is bypassed/crashed in shipment creation
					$track  = $this->_aftershipHelper->saveTrack($magentoTrack);
					$isSent = true;
				} else {

					$track = reset($tracks);

					if ($track->getPosted() == Data::POSTED_NOT_YET) {

						// for the case that the tracking was somehow failed to send to aftership
						$isSent = true;
					}

					// else its done or disabled, do nothing
				}

				if ($isSent) {

					$this->_aftershipHelper->sendTrack($track);
				}
			}
		}

		$this->_eventManager->dispatch('aftership_track_cron_after', ['time' => time()]);
	}

}