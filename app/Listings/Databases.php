<?php

namespace App\Listings;

use App\Models\Database;
use Illuminate\Database\Eloquent\Builder;
use Wyxos\Harmonie\Listing\ListingBase;

class Databases extends ListingBase
{
    public function baseQuery(): Builder
    {
        return Database::query()
            ->with("users");
    }

    public function filters($base)
    {

    }

//    /**
//     * Enables formatting each instance returned by the pagination.
//     */
//    public function append($item)
//    {
//        return $item;
//    }

//    /**
//     * Merge data with the final response.
//     */
//    public function customData(): array
//    {
//        return [];
//    }
}
