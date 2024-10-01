<?php


namespace App\Enum;


enum  UserAccountStatusEnum:string{
    case Active='active';
    case Pending='pending';
    case Blocked='blocked';
    case Banned='banned';
    case Deleted='deleted';
}


?>