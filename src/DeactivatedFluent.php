<?php
namespace AppArk\Database\Eloquent;

use Illuminate\Database\Schema\Blueprint;

class DeactivatedFluent
{
    public function addColumn(Blueprint $table)
    {
        $table->timestampTz('deactivated_at')->nullable();
    }

    public function dropColumn(Blueprint $table)
    {
        $table->dropColumn('deactivated_at');
    }
}
