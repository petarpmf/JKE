<?php
namespace App\Http\Paginate;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class CustomArrayPagination
{
    public function paginate($items,$perPage, $pageName='page')
    {
        $page = Input::get($pageName, 1); // Get the current page or default to 1, this is what you miss!

        // Start displaying items from this number;
        $offSet = ($page * $perPage) - $perPage;

        // Get only the items you need using array_slice
        return new LengthAwarePaginator(array_slice($items, $offSet, $perPage, true), count($items), $perPage, $page, ['path' => Request::url(), 'query' => Request::query()]);
    }
}