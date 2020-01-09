<?php declare(strict_types = 1);

namespace Satesh\Phpcs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Reports\Report;

class SecurityReport implements Report
{
    const SEVERITY = [
        0 => 'Undefined',
        1 => 'Info',
        2 => 'Unknown',
        3 => 'Low',
        4 => 'Medium',
        5 => 'High',
        6 => 'Critical',
    ];

    /**
     * {@inheritDoc}
     */
    public function generateFileReport($report, File $phpcsFile = null, $showSources = false, $width = 80): bool
    {
        foreach ($report['messages'] as $line => $lineErrors) {
            foreach ($lineErrors as $column => $columnErrors) {
                foreach ($columnErrors as $error) {
                    $gitLabError = [
                        'category' => 'sast',
                        'name' => rtrim($error['message'], '.'),
                        'message' => rtrim($error['message'], '.'),
                        'severity' => $this->getSeverity($error),
                        'scanner' => [
                            'id' => 'phpcs_security_audit',
                            'name' => 'phpcs-security-audit native',
                        ],
                        'location' => [
                            'file' => $report['filename'],
                            'start_line' => $line,
                        ],
                        'identifiers' => [
                            "type" => "phpcs_security_audit_source",
                            'name' => $error['source'],
                            'value' => $error['source'],
                        ]
                    ];

                    echo json_encode($gitLabError) . ',';
                }
            }
        }

        return true;
    }

    protected function getSeverity($error) {
        switch ($error['type']) {
            case "ERROR":
                return "High";
            case "WARNING":
                return "Low";
            default:
                return "Undefined";
        }
    }

    /**
     * {@inheritDoc}
     */
    public function generate(
        $cachedData,
        $totalFiles,
        $totalErrors,
        $totalWarnings,
        $totalFixable,
        $showSources = false,
        $width = 80,
        $interactive = false,
        $toScreen = true
    ): void {
        echo '{';
        echo '"version": "2.0", ';
        echo '"vulnerabilities": [' . rtrim($cachedData, ',') . '], ';
        echo '"remediations": []';
        echo '}';
    }
}
