<?php

namespace RCare\System\Support;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait ModelMapper
{
	/**
	 * Create a new model instance containing the data from the request
	 */
	public static function createFromRequest($request, $extra = [])
	{
		$model = (new self())->fillFromRequest($request, $extra);
		$model->save();
		return $model;
	}

	/**
	 * Make a new model instance containing the data from the request
	 */
	public static function makeFromRequest($request, $extra = [])
	{
		return (new self())->fillFromRequest($request, $extra);
	}

	/**
	 * Fill the current instance with the given values
	 */
	public function fillFromRequest($request, $extra = [])
	{
		return $this->fill(self::_map($request, $extra));
	}

	/**
	 * Create multiple model instances from a single request. Useful for arrays
	 */
	public static function createManyFromRequest($request, $extra)
	{
		$created = [];
		foreach ($request[self::$request_key] ?? [] as $field) {			
			$created[] = self::createFromRequest($field, $extra);
		}
		return $created;
	}

	/**
	 * Make multiple model instances from a single request. Useful for arrays
	 */
	public static function makeManyFromRequest($request, $extra)
	{
		$created = [];
		foreach ($request[self::$request_key] ?? [] as $field) {
			$created[] = self::makeFromRequest($field, $extra);
		}
		return $created;
	}

	public function population($formatted = True)
	{
		$staticFields  = [];
		$dynamicFields = [];
		$this->populationMerge($staticFields, $dynamicFields);
		if (!$formatted)
			return [$staticFields, $dynamicFields];
		return populate($staticFields, $dynamicFields);
	}

	/**
	 * Get the data to populate a form
	 */
	public function populationMerge(&$staticFields, &$dynamicFields)
	{
		$dates    = new \Ds\Set($this->dates);
		$mappings = $this->_mappings();
		$prefix = isset($this->prefix) ? $this->prefix . '_' : "";
		foreach (array_merge($this->fillable, $this->population_include ?? []) as $column) {
			$name = isset($mappings[$column]) ? $mappings[$column] : $prefix . $column;
			if (isset($staticFields[$name]))
				continue;
			$staticFields[$name] = $dates->contains($column) ? dateValue($this[$column]): $this[$column];
		}
		foreach ($this->populationRelations() as $relationship) {
			$relation = $this->{$relationship}();
			$name     = get_class_name($relation);
			$this->{"_$name"}($relationship, $this->{$relationship}, $staticFields, $dynamicFields);
		}
	}

	/**
	 * Override this method to return a list of all relationships
	 */
	protected function populationRelations()
	{
		// Example usage where `patients()` returns an Eloquent relationship:
		return [
			// Example value:
			// "patients"
		];
	}

	/**
	 * Map the parameters to the model columns
	 */
	private function _map($request, $extra = [])
	{
		$mappings = $this->_mappings();
		$prefix   = isset($this->prefix) ? $this->prefix . '_' : "";
		$params   = [];
		foreach ($this->fillable as $column) {
			$name = isset($mappings[$column]) ? $mappings[$column] : $prefix . $column;
			if (isset($request[$name])) {
				if (isset($this->dates[$column])) {
					$params[$column] = dateValue($request[$name]);
				} else {
					$params[$column] = $request[$name];
				}
			} else {
				$params[$column] = Null;
			}
		}
		return array_merge($params, $extra);
	}

	/**
	 * Fetch the mappings for the current model if they exist
	 */
	private function _mappings($instance = Null)
	{
		$name = get_class_name($instance ? $instance : self::class);
		return config("model.mappings." . snake_case($name));
	}

	/**
	 * Map one-to-many relationships for population
	 */
	private function _hasMany($name, $items, &$staticFields, &$dynamicFields)
	{
		if (count($items) == 0)
			return;
		$request_key = get_class($items[0])::$request_key ?? str_singular($name);
		$dynamicFields[$request_key] = [];
		foreach ($items as $item) {
			$dynamicFields[$request_key][] = $item->population(False)[0];
		}
	}

	/**
	 * Map one-to-one relationships for
	 */
	private function _hasOne($name, $item, &$staticFields, &$dynamicFields)
	{
		$item->populationMerge($staticFields, $dynamicFields);
	}

	/**
	 * Map belongs-to relationship
	 */
	private function _belongsTo($name, $item, &$staticFields, &$dynamicFields)
	{
		$item->populationMerge($staticFields, $dynamicFields);
	}
}
