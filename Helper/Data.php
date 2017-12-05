<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 5:01 PM
 */

namespace Mrmonsters\Aftership\Helper;

use AfterShip\AfterShipException;
use AfterShip\Trackings;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Mrmonsters\Aftership\Model\TrackFactory;

class Data extends AbstractHelper {

	const ENDPOINT_AUTHENTICATE = 'https://api.aftership.com/v4/couriers';
	const POSTED_NOT_YET        = 0;
	const POSTED_DONE           = 1;
	const POSTED_DISABLED       = 2;

	protected $_storeManager;
	protected $_trackFactory;
	protected $_orderFactory;
	protected $_configHelper;

	/**
	 * @inheritDoc
	 */
	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		TrackFactory $trackFactory,
		OrderFactory $orderFactory,
		Config $configHelper,
		Context $context
	)
	{
		$this->_storeManager = $storeManager;
		$this->_trackFactory = $trackFactory;
		$this->_orderFactory = $orderFactory;
		$this->_configHelper = $configHelper;

		parent::__construct($context);
	}

	public function callApiAuthenticate($apiKey)
	{
		$headers = [
			'aftership-api-key: ' . $apiKey,
			'Content-Type: application/json',
		];
		$ch      = curl_init();

		curl_setopt($ch, CURLOPT_URL, self::ENDPOINT_AUTHENTICATE);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		//handle SSL certificate problem: unable to get local issuer certificate issue
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //the SSL is not correct
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //the SSL is not correct
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response   = curl_exec($ch);
		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return $httpStatus;
	}

	public function saveTrack(Order\Shipment\Track $magentoTrack)
	{
		/* @var Order $order */
		$order = $magentoTrack->getShipment()->getOrder();
		/* @var Order\Address $address */
		$address = $order->getShippingAddress();
		/* @var \Mrmonsters\Aftership\Model\Track $track */
		$track = $this->_trackFactory->create();

		$track->setTrackingNumber($magentoTrack->getTrackNumber());
		$track->setShipCompCode($magentoTrack->getCarrierCode());
		$track->setTitle($order->getIncrementId());
		$track->setOrderId($order->getIncrementId());

		if ($order->getCustomerEmail()) {

			$track->setEmail($order->getCustomerEmail());
		}

		if ($address->getTelephone()) {

			$track->setTelephone($address->getTelephone());
		}

		$enabled = $this->_configHelper->getExtensionEnabled($order->getStore()->getWebsiteId());

		if ($enabled) {

			$track->setPosted(self::POSTED_NOT_YET);
		} else {

			$track->setPosted(self::POSTED_DISABLED);
		}

		$track->save();

		return $track;
	}

	public function sendTrack(\Mrmonsters\Aftership\Model\Track $track)
	{
		if ($track->getPosted() != self::POSTED_NOT_YET) {

			return false;
		}

		/* @var Order $order */
		$order           = $this->_orderFactory->create()->loadByIncrementId($track->getOrderId());
		$shippingAddress = $order->getShippingAddress();
		$apiKey          = $this->_configHelper->getExtensionApiKey($order->getStore()->getWebsiteId());
		$tracking        = new Trackings($apiKey);

		try {

			$response = $tracking->create($track->getTrackingNumber(), [
				'destination_country_iso3' => $shippingAddress->getCountryId(),
				'smses'                    => $shippingAddress->getTelephone(),
				'emails'                   => $order->getCustomerEmail(),
				'title'                    => $track->getOrderId(),
				'order_id'                 => $track->getOrderId(),
				'customer_name'            => $shippingAddress->getFirstname() . ' ' . $shippingAddress->getLastname(),
			]);

			$track->setPosted(self::POSTED_DONE)->save();
		} catch (AfterShipException $e) {


		}
	}

}