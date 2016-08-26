<?php
namespace App\Queries;

use App\Legacy\Client\Client;

class ClientListQuery
{
    public static function perform($rowsPerPage = 10)
    {
        return (new static )->handle($rowsPerPage);
    }
    public function handle($rowsPerPage)
    {

        $query = Client::applyUserFilter()
            ->select('clients.client_id', 'clients.name', 'clients.city')
            ->orderBy('clients.name', 'asc');

        return $query->paginate($rowsPerPage);

    }

}
