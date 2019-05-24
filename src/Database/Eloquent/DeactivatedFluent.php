<?php
namespace AppArk\Database\Eloquent;

use Illuminate\Database\Schema\Blueprint;

class DeactivatedFluent
{
    public static function addColumn(Blueprint $table)
    {
        $table->timestampTz('deactivated_at')->nullable();
    }

    public static function dropColumn(Blueprint $table)
    {
        $table->dropColumn('deactivated_at');
    }
}
