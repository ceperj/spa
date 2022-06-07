<?php

namespace App;

class Constants
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    const USER_ROLE_BLOCKED = 0;
    const USER_ROLE_STANDARD = 1;
    const USER_ROLE_ADMIN = 2;

    public static function getStatusText($value)
    {
        if ($value === null)
            return 'N/D';

        switch($value){
            case Constants::STATUS_ACTIVE: return 'Ativo';
            case Constants::STATUS_INACTIVE: return 'Inativo';
            default: return 'N/D';
        }
    }

    public static function getRoleText($value)
    {
        if ($value === null)
            return 'N/D';

        switch ($value){
            case Constants::USER_ROLE_ADMIN: return 'Administrador';
            case Constants::USER_ROLE_STANDARD: return 'Básico';
            case Constants::USER_ROLE_BLOCKED: return 'Bloqueado';
            default: return 'N/D';
        }
    }
}