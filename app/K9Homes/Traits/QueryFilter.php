<?php

namespace App\K9Homes\Traits;

trait QueryFilter
{

    public function applyFilter($query, $name)
    {
        $data = json_decode(session(env('USER_FILTER_KEY') . '_' . $name, true));

        // dd($data);

        if ($data && isset($data->filters)) {
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

            if (isset($filters->and)) {

                $query->where(function ($query) use ($filters) {

                    foreach ($filters->and as $and) {
                        $query->where([$and]);
                    }
                    return $query;

                });

            }
        }
        return $query;
    }

}
