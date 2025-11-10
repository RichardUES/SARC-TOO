<?php

namespace App\Modules\Reports;

use App\Modules\Reports\ReportsRepository;
use Exception;
use DateTime;

class ReportsService
{
    private ReportsRepository $repository;

    public function __construct()
    {
        $this->repository = new ReportsRepository();
    }

    /**
     * Procesa y estructura los datos de tickets resueltos para reporte
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function procesarTicketsResueltos($fecha_inicio, $fecha_fin)
    {
        try {
            // Validar fechas
            $this->validarFechas($fecha_inicio, $fecha_fin);

            $tickets = $this->repository->getTicketsResueltosRango($fecha_inicio, $fecha_fin);

            // Procesar estadísticas adicionales
            $estadisticas = $this->calcularEstadisticasResolucion($tickets);

            return [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'total_tickets' => count($tickets),
                'tickets' => $tickets,
                'estadisticas' => $estadisticas,
                'fecha_generacion' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en procesarTicketsResueltos: " . $e->getMessage());
            throw new Exception("Error al procesar tickets resueltos: " . $e->getMessage());
        }
    }

    /**
     * Procesa datos de performance de agentes
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function procesarPerformanceAgentes($fecha_inicio, $fecha_fin)
    {
        try {
            // Validar fechas
            $this->validarFechas($fecha_inicio, $fecha_fin);

            $agentes = $this->repository->getPerformanceAgentes($fecha_inicio, $fecha_fin);

            // Calcular rankings y estadísticas adicionales
            $estadisticas = $this->calcularEstadisticasPerformance($agentes);

            // Ordenar agentes por diferentes criterios
            $rankings = $this->generarRankings($agentes);

            return [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'total_agentes' => count($agentes),
                'agentes' => $agentes,
                'estadisticas_globales' => $estadisticas,
                'rankings' => $rankings,
                'fecha_generacion' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en procesarPerformanceAgentes: " . $e->getMessage());
            throw new Exception("Error al procesar performance de agentes: " . $e->getMessage());
        }
    }

    /**
     * Procesa datos de casos escalados
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function procesarCasosEscalados($fecha_inicio, $fecha_fin)
    {
        try {
            // Validar fechas
            $this->validarFechas($fecha_inicio, $fecha_fin);

            $casos = $this->repository->getCasosEscalados($fecha_inicio, $fecha_fin);

            // Procesar estadísticas de escalamiento
            $estadisticas = $this->calcularEstadisticasEscalamiento($casos);

            // Agrupar por áreas y motivos
            $agrupaciones = $this->agruparCasosEscalados($casos);

            return [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'total_casos_escalados' => count($casos),
                'casos' => $casos,
                'estadisticas' => $estadisticas,
                'agrupaciones' => $agrupaciones,
                'fecha_generacion' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en procesarCasosEscalados: " . $e->getMessage());
            throw new Exception("Error al procesar casos escalados: " . $e->getMessage());
        }
    }

    /**
     * Procesa reporte general ejecutivo
     * 
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function procesarReporteGeneral($fecha_inicio, $fecha_fin)
    {
        try {
            // Validar fechas
            $this->validarFechas($fecha_inicio, $fecha_fin);

            $datos = $this->repository->getReporteGeneral($fecha_inicio, $fecha_fin);

            // Calcular métricas adicionales
            $metricas = $this->calcularMetricasEjecutivas($datos['estadisticas_generales']);

            // Procesar tendencias y comparativas
            $analisis = $this->analizarTendencias($datos);

            return [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'estadisticas_generales' => $datos['estadisticas_generales'],
                'estadisticas_agencias' => $datos['estadisticas_agencias'],
                'estadisticas_areas' => $datos['estadisticas_areas'],
                'metricas_ejecutivas' => $metricas,
                'analisis_tendencias' => $analisis,
                'fecha_generacion' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error en procesarReporteGeneral: " . $e->getMessage());
            throw new Exception("Error al procesar reporte general: " . $e->getMessage());
        }
    }

    /**
     * Valida el formato y lógica de las fechas
     */
    private function validarFechas($fecha_inicio, $fecha_fin)
    {
        // Validar formato de fechas
        if (!$this->validarFormatoFecha($fecha_inicio) || !$this->validarFormatoFecha($fecha_fin)) {
            throw new Exception("Formato de fecha inválido. Use el formato Y-m-d (ej: 2025-01-15)");
        }

        // Validar que fecha_inicio sea menor o igual a fecha_fin
        if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
            throw new Exception("La fecha de inicio debe ser menor o igual a la fecha de fin");
        }

        // Validar que las fechas no sean futuras
        if (strtotime($fecha_fin) > time()) {
            throw new Exception("Las fechas no pueden ser futuras");
        }

        // Validar rango máximo (ej: no más de 1 año)
        $diff = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / (60 * 60 * 24);
        if ($diff > 365) {
            throw new Exception("El rango de fechas no puede ser mayor a 365 días");
        }
    }

    /**
     * Valida el formato de una fecha
     */
    private function validarFormatoFecha($fecha)
    {
        $d = DateTime::createFromFormat('Y-m-d', $fecha);
        return $d && $d->format('Y-m-d') === $fecha;
    }

    /**
     * Calcula estadísticas de resolución de tickets
     */
    private function calcularEstadisticasResolucion($tickets)
    {
        if (empty($tickets)) {
            return [
                'tiempo_promedio_horas' => 0,
                'tiempo_minimo_horas' => 0,
                'tiempo_maximo_horas' => 0,
                'distribucion_prioridad' => [],
                'distribucion_origen' => []
            ];
        }

        $tiempos = array_column($tickets, 'HORAS_RESOLUCION');
        $tiempos = array_filter($tiempos, function($t) { return $t !== null; });

        // Distribución por prioridad
        $prioridades = array_count_values(array_column($tickets, 'TKT_PRIORIDAD'));
        
        // Distribución por origen
        $origenes = array_count_values(array_column($tickets, 'TKT_ORIGEN'));

        return [
            'tiempo_promedio_horas' => !empty($tiempos) ? round(array_sum($tiempos) / count($tiempos), 2) : 0,
            'tiempo_minimo_horas' => !empty($tiempos) ? min($tiempos) : 0,
            'tiempo_maximo_horas' => !empty($tiempos) ? max($tiempos) : 0,
            'distribucion_prioridad' => $prioridades,
            'distribucion_origen' => $origenes
        ];
    }

    /**
     * Calcula estadísticas de performance de agentes
     */
    private function calcularEstadisticasPerformance($agentes)
    {
        if (empty($agentes)) {
            return [
                'total_agentes_activos' => 0,
                'promedio_tickets_resueltos' => 0,
                'promedio_eficiencia' => 0,
                'agente_mas_productivo' => null,
                'agente_mas_eficiente' => null
            ];
        }

        $ticketsResueltos = array_column($agentes, 'TICKETS_RESUELTOS');
        $eficiencias = array_filter(array_column($agentes, 'PORCENTAJE_EFICIENCIA'), function($e) { 
            return $e !== null; 
        });

        // Encontrar agente más productivo y eficiente
        $masProductivo = null;
        $masEficiente = null;
        $maxTickets = 0;
        $maxEficiencia = 0;

        foreach ($agentes as $agente) {
            if ($agente['TICKETS_RESUELTOS'] > $maxTickets) {
                $maxTickets = $agente['TICKETS_RESUELTOS'];
                $masProductivo = $agente;
            }
            if ($agente['PORCENTAJE_EFICIENCIA'] > $maxEficiencia) {
                $maxEficiencia = $agente['PORCENTAJE_EFICIENCIA'];
                $masEficiente = $agente;
            }
        }

        return [
            'total_agentes_activos' => count($agentes),
            'promedio_tickets_resueltos' => !empty($ticketsResueltos) ? round(array_sum($ticketsResueltos) / count($ticketsResueltos), 2) : 0,
            'promedio_eficiencia' => !empty($eficiencias) ? round(array_sum($eficiencias) / count($eficiencias), 2) : 0,
            'agente_mas_productivo' => $masProductivo,
            'agente_mas_eficiente' => $masEficiente
        ];
    }

    /**
     * Genera rankings de agentes por diferentes criterios
     */
    private function generarRankings($agentes)
    {
        // Ranking por tickets resueltos
        $porTicketsResueltos = $agentes;
        usort($porTicketsResueltos, function($a, $b) {
            return $b['TICKETS_RESUELTOS'] <=> $a['TICKETS_RESUELTOS'];
        });

        // Ranking por eficiencia
        $porEficiencia = $agentes;
        usort($porEficiencia, function($a, $b) {
            return $b['PORCENTAJE_EFICIENCIA'] <=> $a['PORCENTAJE_EFICIENCIA'];
        });

        // Ranking por tiempo promedio de resolución
        $porTiempo = $agentes;
        usort($porTiempo, function($a, $b) {
            if ($a['TIEMPO_PROMEDIO_RESOLUCION_HORAS'] === null) return 1;
            if ($b['TIEMPO_PROMEDIO_RESOLUCION_HORAS'] === null) return -1;
            return $a['TIEMPO_PROMEDIO_RESOLUCION_HORAS'] <=> $b['TIEMPO_PROMEDIO_RESOLUCION_HORAS'];
        });

        return [
            'por_tickets_resueltos' => array_slice($porTicketsResueltos, 0, 10),
            'por_eficiencia' => array_slice($porEficiencia, 0, 10),
            'por_tiempo_resolucion' => array_slice($porTiempo, 0, 10)
        ];
    }

    /**
     * Calcula estadísticas de escalamiento
     */
    private function calcularEstadisticasEscalamiento($casos)
    {
        if (empty($casos)) {
            return [
                'total_casos' => 0,
                'promedio_horas_antes_escalamiento' => 0,
                'distribucion_por_area' => [],
                'distribucion_por_prioridad' => []
            ];
        }

        $horasAntes = array_filter(array_column($casos, 'HORAS_ANTES_ESCALAMIENTO'), function($h) { 
            return $h !== null; 
        });

        // Distribución por área
        $areas = array_count_values(array_column($casos, 'AREA_ESCALADO'));
        
        // Distribución por prioridad
        $prioridades = array_count_values(array_column($casos, 'TKT_PRIORIDAD'));

        return [
            'total_casos' => count($casos),
            'promedio_horas_antes_escalamiento' => !empty($horasAntes) ? round(array_sum($horasAntes) / count($horasAntes), 2) : 0,
            'distribucion_por_area' => $areas,
            'distribucion_por_prioridad' => $prioridades
        ];
    }

    /**
     * Agrupa casos escalados por diferentes criterios
     */
    private function agruparCasosEscalados($casos)
    {
        $agrupaciones = [
            'por_area' => [],
            'por_agencia' => [],
            'por_estado' => []
        ];

        foreach ($casos as $caso) {
            // Por área
            $area = $caso['AREA_ESCALADO'];
            if (!isset($agrupaciones['por_area'][$area])) {
                $agrupaciones['por_area'][$area] = [];
            }
            $agrupaciones['por_area'][$area][] = $caso;

            // Por agencia
            $agencia = $caso['AGENCIA_NOMBRE'];
            if (!isset($agrupaciones['por_agencia'][$agencia])) {
                $agrupaciones['por_agencia'][$agencia] = [];
            }
            $agrupaciones['por_agencia'][$agencia][] = $caso;

            // Por estado
            $estado = $caso['ESTADO_ACTUAL'];
            if (!isset($agrupaciones['por_estado'][$estado])) {
                $agrupaciones['por_estado'][$estado] = [];
            }
            $agrupaciones['por_estado'][$estado][] = $caso;
        }

        return $agrupaciones;
    }

    /**
     * Calcula métricas ejecutivas
     */
    private function calcularMetricasEjecutivas($estadisticas)
    {
        $total = (int)$estadisticas['TOTAL_TICKETS'];
        $resueltos = (int)$estadisticas['TICKETS_RESUELTOS'];
        $escalados = (int)$estadisticas['TICKETS_ESCALADOS'];

        return [
            'porcentaje_resolucion' => $total > 0 ? round(($resueltos * 100) / $total, 2) : 0,
            'porcentaje_escalamiento' => $total > 0 ? round(($escalados * 100) / $total, 2) : 0,
            'tiempo_promedio_resolucion_dias' => isset($estadisticas['TIEMPO_PROMEDIO_RESOLUCION_HORAS']) ? 
                round($estadisticas['TIEMPO_PROMEDIO_RESOLUCION_HORAS'] / 24, 2) : 0,
            'tiempo_promedio_asignacion_horas' => isset($estadisticas['TIEMPO_PROMEDIO_ASIGNACION_MINUTOS']) ?
                round($estadisticas['TIEMPO_PROMEDIO_ASIGNACION_MINUTOS'] / 60, 2) : 0,
        ];
    }

    /**
     * Analiza tendencias y patrones
     */
    private function analizarTendencias($datos)
    {
        $estadisticas = $datos['estadisticas_generales'];
        
        // Análisis simple de distribución
        $analisis = [
            'canal_predominante' => $this->obtenerCanalPredominante($estadisticas),
            'prioridad_predominante' => $this->obtenerPrioridadPredominante($estadisticas),
            'agencia_mas_activa' => $this->obtenerAgenciaMasActiva($datos['estadisticas_agencias']),
            'area_mas_escalada' => $this->obtenerAreaMasEscalada($datos['estadisticas_areas'])
        ];

        return $analisis;
    }

    private function obtenerCanalPredominante($estadisticas)
    {
        $canales = [
            'WEB' => (int)$estadisticas['ORIGEN_WEB'],
            'LLAMADA' => (int)$estadisticas['ORIGEN_LLAMADA'],
            'PRESENCIAL' => (int)$estadisticas['ORIGEN_PRESENCIAL']
        ];
        return array_search(max($canales), $canales) ?: 'N/A';
    }

    private function obtenerPrioridadPredominante($estadisticas)
    {
        $prioridades = [
            'ALTA' => (int)$estadisticas['PRIORIDAD_ALTA'],
            'MEDIA' => (int)$estadisticas['PRIORIDAD_MEDIA'],
            'BAJA' => (int)$estadisticas['PRIORIDAD_BAJA']
        ];
        return array_search(max($prioridades), $prioridades) ?: 'N/A';
    }

    private function obtenerAgenciaMasActiva($agencias)
    {
        if (empty($agencias)) return 'N/A';
        
        $maxTickets = 0;
        $agenciaMasActiva = null;
        
        foreach ($agencias as $agencia) {
            if ($agencia['TOTAL_TICKETS'] > $maxTickets) {
                $maxTickets = $agencia['TOTAL_TICKETS'];
                $agenciaMasActiva = $agencia['AGE_NOMBRE'];
            }
        }
        
        return $agenciaMasActiva ?: 'N/A';
    }

    private function obtenerAreaMasEscalada($areas)
    {
        if (empty($areas)) return 'N/A';
        
        $maxEscalados = 0;
        $areaMasEscalada = null;
        
        foreach ($areas as $area) {
            if ($area['TOTAL_ESCALADOS'] > $maxEscalados) {
                $maxEscalados = $area['TOTAL_ESCALADOS'];
                $areaMasEscalada = $area['AREA_NOMBRE'];
            }
        }
        
        return $areaMasEscalada ?: 'N/A';
    }
}