<?php

namespace RCare\System\Support;

use Carbon\Carbon;

trait DashboardFetchable {

	public static function countAll(array $params)
	{

	}

	public static function last30Days(array $params)
    {
		$date  = Carbon::now()->subDays(29)->hour(0)->minute(0)->second(0);
		$query = self::where('created_at', '>=', $date);
		return self::fetchWithParams($query, $params);
    }

    public static function last6Months(array $params)
    {
		$date  = Carbon::now()->subMonths(5)->day(1)->hour(0)->minute(0)->second(0);
		$query = self::where('created_at', '>=', $date);
		return self::fetchWithParams($query, $params);
	}

	private static function fetchWithParams($query, $params)
	{
		foreach ($params as $param) {
			$query = $query->where(...$param);
		}
		return $query->get();
	}

	/*
	public function getDatesAttribute($value)
    {
         $this->attributes['created_at'] = Carbon::createFromFormat('m/d/Y', $value);
    }
	*/
}
