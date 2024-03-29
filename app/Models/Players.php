<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
use Prunable;
    use HasFactory;

    protected $fillable = [
        "TransactionType",
        "TransID",
        "TransTime",
        "TransAmount",
        "BusinessShortCode",
        "BillRefNumber",
        "InvoiceNumber",
        "OrgAccountBalance",
        "ThirdPartyTransID",
        "MSISDN",
        "FirstName",
        "MiddleName",
        "LastName",    
    ];
public function prunable(){
return static::where('created_at', '<=', now()->subMonth());
}
}
