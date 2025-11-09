<?php

namespace App\Helpers;

use App\Models\enums\RolType;

class AccessControl
{
    /**
     * Valida si el usuario actual tiene acceso a una funcionalidad específica
     * 
     * @param array $allowedRoles Array de roles permitidos
     * @param bool $redirectOnError Si debe redirigir automáticamente en caso de error
     * @return bool
     */
    public static function validateAccess(array $allowedRoles, bool $redirectOnError = true): bool
    {
        // Verificar si hay sesión activa
        if (!isset($_SESSION["autorizado"]) || !isset($_SESSION["autorizado"]->rolID)) {
            if ($redirectOnError) {
                header("Location: http://luzelfaro.com/errors/unauthorized");
                exit;
            }
            return false;
        }

        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array($_SESSION["autorizado"]->rolID, $allowedRoles)) {
            if ($redirectOnError) {
                header("Location: http://luzelfaro.com/errors/unauthorized");
                exit;
            }
            return false;
        }

        return true;
    }

    /**
     * Validaciones específicas por módulo
     */
    
    public static function validateDashboardAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::ADMIN->value,
            RolType::SUPERVISOR->value
        ], $redirectOnError);
    }

    public static function validateClientRegistrationAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::ADMIN->value,
            RolType::SUPERVISOR->value,
            RolType::AGENT->value
        ], $redirectOnError);
    }

    public static function validateMyTicketsAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::AGENT->value
        ], $redirectOnError);
    }

    public static function validateConsultTicketsAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::ADMIN->value,
            RolType::SUPERVISOR->value,
            RolType::AGENT->value
        ], $redirectOnError);
    }

    public static function validateTicketQueueAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::ADMIN->value,
            RolType::SUPERVISOR->value
        ], $redirectOnError);
    }

    public static function validateReportsAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::ADMIN->value,
            RolType::SUPERVISOR->value
        ], $redirectOnError);
    }

    public static function validateAdministrationAccess(bool $redirectOnError = true): bool
    {
        return self::validateAccess([
            RolType::ADMIN->value
        ], $redirectOnError);
    }

    /**
     * Obtiene información del usuario actual
     */
    public static function getCurrentUser(): ?object
    {
        return $_SESSION["autorizado"] ?? null;
    }

    public static function getCurrentUserRole(): ?int
    {
        return $_SESSION["autorizado"]->rolID ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::getCurrentUserRole() === RolType::ADMIN->value;
    }

    public static function isSupervisor(): bool
    {
        return self::getCurrentUserRole() === RolType::SUPERVISOR->value;
    }

    public static function isAgent(): bool
    {
        return self::getCurrentUserRole() === RolType::AGENT->value;
    }

    public static function isClient(): bool
    {
        return self::getCurrentUserRole() === RolType::CLIENT->value;
    }
}