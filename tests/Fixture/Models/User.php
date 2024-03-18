<?php

namespace Optimacloud\Zoho\Tests\Fixture\Models;

use Optimacloud\Zoho\ZohoManager;
use Optimacloud\Zoho\Traits\Zohoable;
use Optimacloud\Zoho\Models\Zohoable as ZohoableModel;
use Optimacloud\Zoho\Contracts\Repositories\ZohoableRepository;

class User extends ZohoableModel implements ZohoableRepository
{
    use Zohoable;

    protected string $zoho_module_name = 'Contacts';

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'zoho_id'];

    public function zohoMandatoryFields(): array
    {
        return [
            'First_Name' => $this->first_name,
            'Last_Name'  => $this->last_name,
            'Email'      => $this->email,
            'Phone'      => $this->phone,
        ];
    }

    public function searchCriteria()
    {
        ZohoManager::make($this->zoho_module_name)
                   ->where('First_Name', $this->first_name)
                   ->andWhere('Last_Name', $this->last_name)
                   ->getCriteria();
    }
}
