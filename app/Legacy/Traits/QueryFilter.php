<?php

namespace App\Legacy\Traits;

trait QueryFilter
{

    public function applyFilter($query, $name)
    {

        $data = $this->getQueryFilterData($name);

        $query = $this->applyOrFilter($query, $data);

        $query = $this->applyAndFilter($query, $data);

        return $query;

    }

    protected function applyOrFilter($query, $data)
    {
        if (!$data || !$data->filters) {
            return $query;
        }

        $filters = $data->filters;
        //dd($filters->or[1]);

        if (isset($filters->or)) {

            $query->where(function ($query) use ($filters) {

                foreach ($filters->or as $or) {
                    $query->orWhere([$or]);
                }

                return $query;
            });
        }

        return $query;
    }

    protected function applyAndFilter($query, $data)
    {
        if (!$data || !$data->filters) {
            return $query;
        }

        $filters = $data->filters;

        if (isset($filters->and)) {

            $query->where(function ($query) use ($filters) {

                foreach ($filters->and as $and) {
                    $query->where([$and]);
                }

                return $query;

            });

        }
        return $query;
    }

    protected function getQueryFilterData($name)
    {
        $sessionKey = env('USER_FILTER_KEY') . '_' . $name;
        $sessionData = session()->get($sessionKey, false);
        return $sessionData ? json_decode($sessionData) : $sessionData;
    }

}
