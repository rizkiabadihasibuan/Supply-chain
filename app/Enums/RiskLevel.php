<?php
namespace App\Enums;
/** RiskLevel – Tingkat risiko rantai pasok */
enum RiskLevel: string {
    case Low      = 'low';
    case Medium   = 'medium';
    case High     = 'high';
    case Critical = 'critical';
}
