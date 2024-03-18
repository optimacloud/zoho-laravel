<?php

namespace Optimacloud\Zoho\Models;

use Optimacloud\Zoho\Contracts\Repositories\ZohoableRepository;
use Optimacloud\Zoho\Traits\Zohoable as ZohoableModel;
use Optimacloud\Zoho\ZohoManager;
use Illuminate\Database\Eloquent\Model;

abstract class Zohoable extends Model implements ZohoableRepository
{
    use ZohoableModel;

    protected string $zoho_module_name;
    protected ZohoManager $zoho_module;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->zoho_module = $this->getZohoModule();
    }
}
