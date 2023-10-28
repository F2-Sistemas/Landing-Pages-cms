<?php

function mostraStatusAcao(int $inicioP, int $terminoP, int $inicioR, int $terminoR, bool $mostrarNaoIniciada, bool $mostrarConcluidaAtraso, bool $mostrarAndamentoAtraso, ?int $dataReferencia = null)
{
    // - [ 1 ] Planejada: Data de início e término planejado no futuro
    // - [ 2 ] Não iniciada: Data de início planejado no passado e término planejado no futuro, mas sem data de início real
    // - [ 3 ] Em andamento: Data de término planejado no futuro e com data de início real preenchida
    // - [ 4 ] Em andamento com Atraso
    // - [ 4 ] Atrasada: Data de termino prevista no passado e termino Real vazia
    // - [ 5 ] Concluída: Todas as datas preenchidas
    // - [ 6 ] Concluída com atraso: Todas as datas preenchidas, mas termino real apos o termino previsto

    if (empty($dataReferencia)) {
        $agora = new DateTime('now');
        $today = strtotime($agora->format('Y-m-d'));
    } else {
        $today = $dataReferencia;
    }

    if (!empty($terminoR)) {
        // 6 - Concluída | 7 - Concluída com atraso
        if ($mostrarConcluidaAtraso && $terminoR > $terminoP) {
            $status = 7;
        } else {
            $status = 6;
        }
    } elseif (empty($inicioR)) {
        // 1. Planejada | 2. Não Iniciada | 5. Atrasada
        if ($terminoP < $today) {
            $status = 5;
        } else {
            if ($mostrarNaoIniciada && $inicioP < $today) {
                $status = 2;
            } else {
                $status = 1;
            }
        }
    } else {
        // 3. Em Andamento | 5. Atrasada | 4. Em Andamento com atraso
        if ($terminoP < $today) {
            $status = 5;
        } else {
            if ($mostrarAndamentoAtraso && $inicioR > $inicioP) {
                $status = 4;
            } else {
                $status = 3;
            }
        }
    }

    return $status;
}

$mostrarNaoIniciada = false;
$mostrarConcluidaAtraso = false;
$mostrarAndamentoAtraso = false;
$inicioP = strtotime('2023/01/01');
$terminoP = strtotime('2024/01/01');
$inicioR = strtotime('2024/01/01');
$terminoR = strtotime('2024/01/02');

echo mostraStatusAcao($inicioP, $terminoP, $inicioR, $terminoR, $mostrarNaoIniciada, $mostrarConcluidaAtraso, $mostrarAndamentoAtraso);
