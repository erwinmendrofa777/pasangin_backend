<?php

namespace App\HTTP;

use CodeIgniter\HTTP\IncomingRequest as CIIncomingRequest;

#[\AllowDynamicProperties]
class IncomingRequest extends CIIncomingRequest
{
    // By adding the AllowDynamicProperties attribute, PHP 8.2+ allows setting 
    // dynamic properties (like $request->user) without throwing deprecation warnings.
}
