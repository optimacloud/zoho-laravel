<?php

namespace Optimacloud\Zoho\Http\Controllers;

use Optimacloud\Zoho\Http\Requests\ZohoRedirectRequest;
use Optimacloud\Zoho\Zoho;
use com\zoho\crm\api\exception\SDKException;
use Illuminate\Routing\Controller;

class ZohoController extends Controller
{
    public function oauth2callback(ZohoRedirectRequest $request)
    {
        try {
            Zoho::initialize($request->code);
        } catch (SDKException $e) {
            return 'Error while setting up Zoho CRM: '.$e->getMessage();
        }

        return 'Zoho CRM has been set up successfully.';
    }
}
