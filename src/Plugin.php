<?php

namespace Botble\Membership;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('membership_histories');
        Schema::dropIfExists('membership_product_prices');
        // Note: We preserve memberships and plans unless explicitly dropped to protect business data
    }
}
