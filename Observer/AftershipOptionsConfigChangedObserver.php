<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 4:29 PM
 */

namespace Mrmonsters\Aftership\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Mrmonsters\Aftership\Helper\Data;

class AftershipOptionsConfigChangedObserver implements ObserverInterface {

	protected $_aftershipHelper;

	/**
	 * AftershipOptionsConfigChanged constructor.
	 *
	 * @param \Mrmonsters\Aftership\Helper\Data $_aftershipHelper
	 */
	public function __construct(Data $helper)
	{
		$this->_aftershipHelper = $helper;
	}

	/**
	 * @inheritDoc
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$postData = $_POST;

		if (!isset($postData['groups']['messages']['fields']['api_key']['inherit']) ||
			$postData['groups']['messages']['fields']['api_key']['inherit'] != 1
		) {

			$apiKey     = $postData['groups']['messages']['fields']['api_key']['value'];
			$httpStatus = $this->_aftershipHelper->callApiAuthenticate($apiKey);

			if ($httpStatus == '401') {

				throw new LocalizedException(__('Incorrect API Key'));
			} else if ($httpStatus != '200') {

				throw new LocalizedException(__('Connection error, please try again later.'));
			}
		}
	}

}