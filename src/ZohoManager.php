<?php

namespace Optimacloud\Zoho;

use Optimacloud\Zoho\Concerns\CriteriaBuilder;
use Optimacloud\Zoho\Concerns\ManagesActions;
use Optimacloud\Zoho\Concerns\ManagesBulkActions;
use Optimacloud\Zoho\Concerns\ManagesModules;
use Optimacloud\Zoho\Concerns\ManagesRecords;
use Optimacloud\Zoho\Concerns\ManagesTags;
use com\zoho\crm\api\exception\SDKException;

class ZohoManager
{
    use CriteriaBuilder;
    use ManagesModules;
    use ManagesRecords;
    use ManagesTags;
    use ManagesActions;
    use ManagesBulkActions;

    protected string $module_api_name;

    public function __construct($module_api_name = 'Leads')
    {
        try {
            $this->module_api_name = $module_api_name;
            Zoho::initialize();
        } catch (SDKException $e) {
            //
        }
    }

    public static function useModule(string $module_name = 'Leads'): static
    {
        return new static($module_name);
    }

    public static function make(string $module_name = 'Leads'): static
    {
        return static::useModule($module_name);
    }
}
