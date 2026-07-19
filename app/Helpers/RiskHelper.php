<?php
namespace App\Helpers;
/**
 * RiskHelper – Helper untuk kalkulasi dan format risk score
 * TODO (Backend Phase): Implementasi kalkulasi aktual
 */
class RiskHelper {
    public static function levelFromScore(float $score): string {
        return match(true) {
            $score >= 80 => 'critical',
            $score >= 60 => 'high',
            $score >= 30 => 'medium',
            default      => 'low',
        };
    }
    public static function badgeColor(string $level): string {
        return match($level) {
            'critical' => 'danger', 'high' => 'warning', 'medium' => 'info', default => 'success',
        };
    }
}
