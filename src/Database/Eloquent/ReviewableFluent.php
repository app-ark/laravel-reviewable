<?php
namespace AppArk\Database\Eloquent;

use Illuminate\Database\Schema\Blueprint;

class ReviewableFluent
{
    public static function addColumn(Blueprint $table)
    {
        $table->tinyInteger('review')->nullable();
    }

    public static function dropColumn(Blueprint $table)
    {
        $table->dropColumn('review');
    }
}
