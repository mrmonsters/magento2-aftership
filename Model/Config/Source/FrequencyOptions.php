<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 1:05 PM
 */

namespace Mrmonsters\Aftership\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class FrequencyOptions implements ArrayInterface {

	/**
	 * @inheritDoc
	 */
	public function toOptionArray()
	{
		return [
			[
				'value' => 0,
				'label' => __('Every 30 minutes'),
			],
			[
				'value' => 1,
				'label' => __('Every 60 minutes'),
			],
			[
				'value' => 2,
				'label' => __('Every 6 hours'),
			],
			[
				'value' => 3,
				'label' => __('Every 12 hours'),
			],
			[
				'value' => 4,
				'label' => __('Every 24 hours'),
			],
		];
	}

}