<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 5:44 PM
 */

namespace Mrmonsters\Aftership\Api\Data;

interface TrackInterface {

	const TRACK_ID        = 'track_id';
	const TRACKING_NUMBER = 'tracking_number';
	const SHIP_COMP_CODE  = 'ship_comp_code';
	const EMAIL           = 'email';
	const TELEPHONE       = 'telephone';
	const TITLE           = 'title';
	const POSTED          = 'posted';
	const ORDER_ID        = 'order_id';

	/**
	 * @return int|null
	 */
	public function getTrackId();

	/**
	 * @return string|null
	 */
	public function getTrackingNumber();

	/**
	 * @return string|null
	 */
	public function getShipCompCode();

	/**
	 * @return string|null
	 */
	public function getEmail();

	/**
	 * @return string|null
	 */
	public function getTelephone();

	/**
	 * @return string|null
	 */
	public function getTitle();

	/**
	 * @return int|null
	 */
	public function getPosted();

	/**
	 * @return string|null
	 */
	public function getOrderId();

	/**
	 * @param string $trackId
	 *
	 * @return TrackInterface
	 */
	public function setTrackId($trackId);

	/**
	 * @param string $trackNo
	 *
	 * @return TrackInterface
	 */
	public function setTrackingNumber($trackNo);

	/**
	 * @param string $code
	 *
	 * @return TrackInterface
	 */
	public function setShipCompCode($code);

	/**
	 * @param string $email
	 *
	 * @return TrackInterface
	 */
	public function setEmail($email);

	/**
	 * @param string $telephone
	 *
	 * @return TrackInterface
	 */
	public function setTelephone($telephone);

	/**
	 * @param string $title
	 *
	 * @return TrackInterface
	 */
	public function setTitle($title);

	/**
	 * @param int $posted
	 *
	 * @return TrackInterface
	 */
	public function setPosted($posted);

	/**
	 * @param string $orderId
	 *
	 * @return TrackInterface
	 */
	public function setOrderId($orderId);

}