<?php

namespace App\Modules\Reports;

use App\Config\Database;
use PDO;
use Exception;

class ReportsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getIntance()->getConnection();
    }

    /**
     * Obtiene tickets resueltos en un rango de fechas con información completa
     * 
     * @param string $fecha_inicio Fecha de inicio en formato Y-m-d
     * @param string $fecha_fin Fecha de fin en formato Y-m-d
     * @return array Array con los tickets resueltos
     */
    public function getTicketsResueltosRango($fecha_inicio, $fecha_fin)
    {
        try {
            $sql = "SELECT 
                        T.TKT_CODIGO,
                        T.TKT_ASUNTO,
                        T.TKT_DESCRIPCION,
                        T.TKT_PRIORIDAD,
                        T.TKT_ORIGEN,
                        T.TKT_FECHA_CREACION,
                        T.TKT_FECHA_ASIGNACION,
                        T.TKT_FECHA_CIERRE,
                        -- Información del cliente
                        CONCAT(C.CLI_PRIMER_NOM, ' ', 
                               COALESCE(C.CLI_SEGUNDO_NOM, ''), ' ',
                               C.CLI_PRIMER_APE, ' ', 
                               COALESCE(C.CLI_SEGUNDO_APE, '')) AS CLIENTE_NOMBRE_COMPLETO,
                        U_CLIENTE.USU_EMAIL AS CLIENTE_EMAIL,
                        C.CLI_DUI,
                        C.CLI_TELEFONO,
                        -- Información de la agencia
                        A.AGE_NOMBRE AS AGENCIA_NOMBRE,
                        -- Información del agente asignado
                        CONCAT(U_AGENTE.USU_USERNAME) AS AGENTE_USUARIO,
                        U_AGENTE.USU_EMAIL AS AGENTE_EMAIL,
                        -- Estado actual
                        EST.EST_NOMBRE AS ESTADO_ACTUAL,
                        -- Área (si fue escalado)
                        COALESCE(AR.AREA_NOMBRE, 'NO ESCALADO') AS AREA_ESCALADO,
                        -- Tiempo de resolución
                        TIMESTAMPDIFF(HOUR, T.TKT_FECHA_CREACION, T.TKT_FECHA_CIERRE) AS HORAS_RESOLUCION,
                        TIMESTAMPDIFF(DAY, T.TKT_FECHA_CREACION, T.TKT_FECHA_CIERRE) AS DIAS_RESOLUCION
                    FROM TICKETS T
                    -- Join con cliente
                    INNER JOIN CLIENTES C ON T.TKT_CLIENTE_ID = C.CLI_CODIGO
                    INNER JOIN USUARIOS U_CLIENTE ON C.CLI_USUARIO_ID = U_CLIENTE.USU_CODIGO
                    -- Join con agencia
                    INNER JOIN AGENCIAS A ON T.TKT_AGENCIA_ID = A.AGE_CODIGO
                    -- Join con estado
                    INNER JOIN ESTADO_TICKET EST ON T.TKT_ESTADO_ID = EST.EST_CODIGO
                    -- Join con área (opcional)
                    LEFT JOIN AREAS AR ON T.TKT_AREA_ID = AR.AREA_CODIGO
                    -- Join con asignación actual
                    LEFT JOIN ASIGNACION_TICKET ASG ON T.TKT_CODIGO = ASG.ASIG_TKT_ID AND ASG.ASIG_FINALIZADA = 'N'
                    LEFT JOIN USUARIOS U_AGENTE ON ASG.ASIG_USUARIO_ID = U_AGENTE.USU_CODIGO
                    WHERE T.TKT_FECHA_CIERRE IS NOT NULL 
                    AND EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO')
                    AND DATE(T.TKT_FECHA_CIERRE) BETWEEN :fecha_inicio AND :fecha_fin
                    AND T.TKT_ESTADO_LOGICO = 'ACTIVO'
                    ORDER BY T.TKT_FECHA_CIERRE DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getTicketsResueltosRango: " . $e->getMessage());
            throw new Exception("Error al obtener tickets resueltos: " . $e->getMessage());
        }
    }

    /**
     * Obtiene estadísticas de performance por agente en un rango de fechas
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getPerformanceAgentes($fecha_inicio, $fecha_fin)
    {
        try {
            $sql = "SELECT 
                        U.USU_CODIGO,
                        U.USU_USERNAME AS AGENTE_USUARIO,
                        U.USU_EMAIL AS AGENTE_EMAIL,
                        A.AGE_NOMBRE AS AGENCIA_NOMBRE,
                        -- Estadísticas de tickets
                        COUNT(DISTINCT CASE WHEN ASG.ASIG_TKT_ID IS NOT NULL THEN T.TKT_CODIGO END) AS TICKETS_ASIGNADOS,
                        COUNT(DISTINCT CASE WHEN T.TKT_FECHA_CIERRE IS NOT NULL AND EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO') THEN T.TKT_CODIGO END) AS TICKETS_RESUELTOS,
                        COUNT(DISTINCT CASE WHEN EST.EST_NOMBRE IN ('ASIGNADO', 'EN_PROCESO') THEN T.TKT_CODIGO END) AS TICKETS_PENDIENTES,
                        COUNT(DISTINCT CASE WHEN EST.EST_NOMBRE = 'ESCALADO' THEN T.TKT_CODIGO END) AS TICKETS_ESCALADOS,
                        -- Tiempos promedio
                        ROUND(AVG(CASE 
                            WHEN T.TKT_FECHA_CIERRE IS NOT NULL 
                            THEN TIMESTAMPDIFF(HOUR, T.TKT_FECHA_CREACION, T.TKT_FECHA_CIERRE) 
                            END), 2) AS TIEMPO_PROMEDIO_RESOLUCION_HORAS,
                        -- Carga de trabajo actual
                        SUM(CASE WHEN EST.EST_NOMBRE IN ('ASIGNADO', 'EN_PROCESO') THEN 1 ELSE 0 END) AS CARGA_ACTUAL,
                        -- Eficiencia (porcentaje de tickets resueltos)
                        ROUND(
                            (COUNT(DISTINCT CASE WHEN T.TKT_FECHA_CIERRE IS NOT NULL AND EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO') THEN T.TKT_CODIGO END) * 100.0) / 
                            NULLIF(COUNT(DISTINCT CASE WHEN ASG.ASIG_TKT_ID IS NOT NULL THEN T.TKT_CODIGO END), 0), 
                            2
                        ) AS PORCENTAJE_EFICIENCIA
                    FROM USUARIOS U
                    INNER JOIN ROLES R ON U.USU_ROL_ID = R.ROL_CODIGO
                    LEFT JOIN AGENCIAS A ON U.USU_AGENCIA_ID = A.AGE_CODIGO
                    LEFT JOIN ASIGNACION_TICKET ASG ON U.USU_CODIGO = ASG.ASIG_USUARIO_ID
                    LEFT JOIN TICKETS T ON ASG.ASIG_TKT_ID = T.TKT_CODIGO AND T.TKT_ESTADO_LOGICO = 'ACTIVO'
                    LEFT JOIN ESTADO_TICKET EST ON T.TKT_ESTADO_ID = EST.EST_CODIGO
                    WHERE R.ROL_NOMBRE = 'Agente'
                    AND U.USU_ESTADO = 'ACTIVO'
                    AND (T.TKT_CODIGO IS NULL OR 
                         DATE(T.TKT_FECHA_CREACION) BETWEEN :fecha_inicio AND :fecha_fin)
                    GROUP BY U.USU_CODIGO, U.USU_USERNAME, U.USU_EMAIL, A.AGE_NOMBRE
                    ORDER BY TICKETS_RESUELTOS DESC, PORCENTAJE_EFICIENCIA DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getPerformanceAgentes: " . $e->getMessage());
            throw new Exception("Error al obtener performance de agentes: " . $e->getMessage());
        }
    }

    /**
     * Obtiene casos escalados con información detallada
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getCasosEscalados($fecha_inicio, $fecha_fin)
    {
        try {
            $sql = "SELECT 
                        T.TKT_CODIGO,
                        T.TKT_ASUNTO,
                        T.TKT_DESCRIPCION,
                        T.TKT_PRIORIDAD,
                        T.TKT_FECHA_CREACION,
                        T.TKT_FECHA_ASIGNACION,
                        -- Información del cliente
                        CONCAT(C.CLI_PRIMER_NOM, ' ', 
                               COALESCE(C.CLI_SEGUNDO_NOM, ''), ' ',
                               C.CLI_PRIMER_APE, ' ', 
                               COALESCE(C.CLI_SEGUNDO_APE, '')) AS CLIENTE_NOMBRE_COMPLETO,
                        C.CLI_DUI,
                        U_CLIENTE.USU_EMAIL AS CLIENTE_EMAIL,
                        -- Información de agencia y área
                        A.AGE_NOMBRE AS AGENCIA_NOMBRE,
                        AR.AREA_NOMBRE AS AREA_ESCALADO,
                        AR.AREA_DESCRIPCION,
                        -- Información del agente que escaló
                        U_AGENTE.USU_USERNAME AS AGENTE_ESCALADOR,
                        -- Información del historial de escalamiento
                        H.HIST_FECHA AS FECHA_ESCALAMIENTO,
                        H.HIST_COMENTARIO AS MOTIVO_ESCALAMIENTO,
                        -- Estado actual
                        EST.EST_NOMBRE AS ESTADO_ACTUAL,
                        -- Tiempo transcurrido desde escalamiento
                        TIMESTAMPDIFF(DAY, H.HIST_FECHA, NOW()) AS DIAS_DESDE_ESCALAMIENTO,
                        TIMESTAMPDIFF(HOUR, H.HIST_FECHA, NOW()) AS HORAS_DESDE_ESCALAMIENTO,
                        -- Tiempo antes del escalamiento
                        TIMESTAMPDIFF(HOUR, T.TKT_FECHA_CREACION, H.HIST_FECHA) AS HORAS_ANTES_ESCALAMIENTO
                    FROM TICKETS T
                    -- Join con cliente
                    INNER JOIN CLIENTES C ON T.TKT_CLIENTE_ID = C.CLI_CODIGO
                    INNER JOIN USUARIOS U_CLIENTE ON C.CLI_USUARIO_ID = U_CLIENTE.USU_CODIGO
                    -- Join con agencia
                    INNER JOIN AGENCIAS A ON T.TKT_AGENCIA_ID = A.AGE_CODIGO
                    -- Join con área (debe existir para casos escalados)
                    INNER JOIN AREAS AR ON T.TKT_AREA_ID = AR.AREA_CODIGO
                    -- Join con estado
                    INNER JOIN ESTADO_TICKET EST ON T.TKT_ESTADO_ID = EST.EST_CODIGO
                    -- Join con historial para obtener información del escalamiento
                    INNER JOIN HISTORIAL_TICKET H ON T.TKT_CODIGO = H.HIST_TKT_ID
                    INNER JOIN ESTADO_TICKET EST_NUEVO ON H.HIST_ESTADO_NUEVO = EST_NUEVO.EST_CODIGO
                    -- Join con agente que realizó el escalamiento
                    LEFT JOIN USUARIOS U_AGENTE ON H.HIST_USUARIO_ID = U_AGENTE.USU_CODIGO
                    WHERE EST_NUEVO.EST_NOMBRE = 'ESCALADO'
                    AND T.TKT_ESTADO_LOGICO = 'ACTIVO'
                    AND DATE(H.HIST_FECHA) BETWEEN :fecha_inicio AND :fecha_fin
                    ORDER BY H.HIST_FECHA DESC, T.TKT_PRIORIDAD DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getCasosEscalados: " . $e->getMessage());
            throw new Exception("Error al obtener casos escalados: " . $e->getMessage());
        }
    }

    /**
     * Obtiene estadísticas generales del sistema para reporte ejecutivo
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getReporteGeneral($fecha_inicio, $fecha_fin)
    {
        try {
            // Estadísticas principales
            $sqlGeneral = "SELECT 
                            -- Tickets por estado
                            COUNT(CASE WHEN EST.EST_NOMBRE = 'RECIBIDO' THEN 1 END) AS TICKETS_RECIBIDOS,
                            COUNT(CASE WHEN EST.EST_NOMBRE = 'ASIGNADO' THEN 1 END) AS TICKETS_ASIGNADOS,
                            COUNT(CASE WHEN EST.EST_NOMBRE = 'EN_PROCESO' THEN 1 END) AS TICKETS_EN_PROCESO,
                            COUNT(CASE WHEN EST.EST_NOMBRE = 'ESCALADO' THEN 1 END) AS TICKETS_ESCALADOS,
                            COUNT(CASE WHEN EST.EST_NOMBRE = 'PENDIENTE' THEN 1 END) AS TICKETS_PENDIENTES,
                            COUNT(CASE WHEN EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO') THEN 1 END) AS TICKETS_RESUELTOS,
                            -- Totales
                            COUNT(*) AS TOTAL_TICKETS,
                            -- Por prioridad
                            COUNT(CASE WHEN T.TKT_PRIORIDAD = 'ALTA' THEN 1 END) AS PRIORIDAD_ALTA,
                            COUNT(CASE WHEN T.TKT_PRIORIDAD = 'MEDIA' THEN 1 END) AS PRIORIDAD_MEDIA,
                            COUNT(CASE WHEN T.TKT_PRIORIDAD = 'BAJA' THEN 1 END) AS PRIORIDAD_BAJA,
                            -- Por origen
                            COUNT(CASE WHEN T.TKT_ORIGEN = 'WEB' THEN 1 END) AS ORIGEN_WEB,
                            COUNT(CASE WHEN T.TKT_ORIGEN = 'LLAMADA' THEN 1 END) AS ORIGEN_LLAMADA,
                            COUNT(CASE WHEN T.TKT_ORIGEN = 'PRESENCIAL' THEN 1 END) AS ORIGEN_PRESENCIAL,
                            -- Tiempos promedio
                            ROUND(AVG(CASE 
                                WHEN T.TKT_FECHA_CIERRE IS NOT NULL 
                                THEN TIMESTAMPDIFF(HOUR, T.TKT_FECHA_CREACION, T.TKT_FECHA_CIERRE) 
                                END), 2) AS TIEMPO_PROMEDIO_RESOLUCION_HORAS,
                            ROUND(AVG(CASE 
                                WHEN T.TKT_FECHA_ASIGNACION IS NOT NULL 
                                THEN TIMESTAMPDIFF(MINUTE, T.TKT_FECHA_CREACION, T.TKT_FECHA_ASIGNACION) 
                                END), 2) AS TIEMPO_PROMEDIO_ASIGNACION_MINUTOS
                        FROM TICKETS T
                        INNER JOIN ESTADO_TICKET EST ON T.TKT_ESTADO_ID = EST.EST_CODIGO
                        WHERE DATE(T.TKT_FECHA_CREACION) BETWEEN :fecha_inicio AND :fecha_fin
                        AND T.TKT_ESTADO_LOGICO = 'ACTIVO'";

            $stmt = $this->pdo->prepare($sqlGeneral);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            $estadisticasGenerales = $stmt->fetch(PDO::FETCH_ASSOC);

            // Estadísticas por agencia
            $sqlAgencias = "SELECT 
                                A.AGE_NOMBRE,
                                COUNT(T.TKT_CODIGO) AS TOTAL_TICKETS,
                                COUNT(CASE WHEN EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO') THEN 1 END) AS TICKETS_RESUELTOS,
                                ROUND(
                                    (COUNT(CASE WHEN EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO') THEN 1 END) * 100.0) / 
                                    NULLIF(COUNT(T.TKT_CODIGO), 0), 
                                    2
                                ) AS PORCENTAJE_RESOLUCION
                            FROM AGENCIAS A
                            LEFT JOIN TICKETS T ON A.AGE_CODIGO = T.TKT_AGENCIA_ID 
                                AND DATE(T.TKT_FECHA_CREACION) BETWEEN :fecha_inicio AND :fecha_fin
                                AND T.TKT_ESTADO_LOGICO = 'ACTIVO'
                            LEFT JOIN ESTADO_TICKET EST ON T.TKT_ESTADO_ID = EST.EST_CODIGO
                            WHERE A.AGE_ESTADO = 'ACTIVO'
                            GROUP BY A.AGE_CODIGO, A.AGE_NOMBRE
                            ORDER BY TOTAL_TICKETS DESC";

            $stmt = $this->pdo->prepare($sqlAgencias);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            $estadisticasAgencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Estadísticas por área (escalamientos)
            $sqlAreas = "SELECT 
                            AR.AREA_NOMBRE,
                            COUNT(T.TKT_CODIGO) AS TOTAL_ESCALADOS,
                            COUNT(CASE WHEN EST.EST_NOMBRE IN ('COMPLETADO', 'RESUELTO') THEN 1 END) AS ESCALADOS_RESUELTOS,
                            ROUND(AVG(CASE 
                                WHEN T.TKT_FECHA_CIERRE IS NOT NULL 
                                THEN TIMESTAMPDIFF(HOUR, T.TKT_FECHA_CREACION, T.TKT_FECHA_CIERRE) 
                                END), 2) AS TIEMPO_PROMEDIO_RESOLUCION_HORAS
                        FROM AREAS AR
                        LEFT JOIN TICKETS T ON AR.AREA_CODIGO = T.TKT_AREA_ID 
                            AND DATE(T.TKT_FECHA_CREACION) BETWEEN :fecha_inicio AND :fecha_fin
                            AND T.TKT_ESTADO_LOGICO = 'ACTIVO'
                        LEFT JOIN ESTADO_TICKET EST ON T.TKT_ESTADO_ID = EST.EST_CODIGO
                        WHERE AR.AREA_ESTADO = 'ACTIVO'
                        GROUP BY AR.AREA_CODIGO, AR.AREA_NOMBRE
                        HAVING TOTAL_ESCALADOS > 0
                        ORDER BY TOTAL_ESCALADOS DESC";

            $stmt = $this->pdo->prepare($sqlAreas);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            $estadisticasAreas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'estadisticas_generales' => $estadisticasGenerales,
                'estadisticas_agencias' => $estadisticasAgencias,
                'estadisticas_areas' => $estadisticasAreas
            ];

        } catch (Exception $e) {
            error_log("Error en getReporteGeneral: " . $e->getMessage());
            throw new Exception("Error al obtener reporte general: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la lista de agencias activas
     */
    public function getAgenciasActivas()
    {
        try {
            $sql = "SELECT AGE_CODIGO, AGE_NOMBRE FROM AGENCIAS WHERE AGE_ESTADO = 'ACTIVO' ORDER BY AGE_NOMBRE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAgenciasActivas: " . $e->getMessage());
            throw new Exception("Error al obtener agencias activas: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la lista de áreas activas
     */
    public function getAreasActivas()
    {
        try {
            $sql = "SELECT AREA_CODIGO, AREA_NOMBRE FROM AREAS WHERE AREA_ESTADO = 'ACTIVO' ORDER BY AREA_NOMBRE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAreasActivas: " . $e->getMessage());
            throw new Exception("Error al obtener áreas activas: " . $e->getMessage());
        }
    }

    /**
     * Obtiene estados de tickets disponibles
     */
    public function getEstadosTicket()
    {
        try {
            $sql = "SELECT EST_CODIGO, EST_NOMBRE, EST_DESCRIPCION FROM ESTADO_TICKET ORDER BY EST_NOMBRE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getEstadosTicket: " . $e->getMessage());
            throw new Exception("Error al obtener estados de ticket: " . $e->getMessage());
        }
    }
}
