<?php

namespace RCare\System\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * This trait grants the ability to automatically generate fillable columns for your model
 */
class GeneratedFillableModel extends Model
{
	/**
	 * Override the constructor to generate some fillable attributes
	 */
	public function __construct(array $attributes = [])
	{
		foreach ($this->generateFillables() as $field) {
			$this->fillable[] = $field;
		}
		parent::__construct($attributes);
	}

	/**
	 * Generate some new fillable attributes for your model. Override this in your model
	 */
	protected function generateFillables()
	{}
}
