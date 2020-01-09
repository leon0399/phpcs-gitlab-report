<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Satesh\Phpcs\CodeQualityReport;
use Satesh\Phpcs\SecurityReport;

class SecurityReportTest extends TestCase
{
    public function testGenerateFileReport(): string
    {
        $reportOutput = $this->getGitLabReport();

        $this->expectOutputString($reportOutput);

        $gitLabReport = new SecurityReport();

        $returnValue = $gitLabReport->generateFileReport($this->getPhpcsReport());

        static::assertTrue($returnValue);

        return $reportOutput;
    }

    /**
     * @depends testGenerateFileReport
     */
    public function testGenerate(string $fileReport): void
    {
        $this->expectOutputString('[' . rtrim($fileReport, ',') . ']');

        $gitLabReport = new SecurityReport();

        $gitLabReport->generate($fileReport, 1, 1, 1, 1);
    }

    private function getGitLabReport(): string
    {
        return '{"category":"sast","name":"PHP files must only contain PHP code","message":"PHP files must only contain PHP code","severity":"High","scanner":{"id":"phpcs_security_audit","name":"phpcs-security-audit native"},"location":{"file":"files\/TestClass.php","start_line":3},"identifiers":{"type":"phpcs_security_audit_source","name":"Generic.Files.InlineHTML.Found","value":"Generic.Files.InlineHTML.Found"}},';
    }

    private function getPhpcsReport(): array
    {
        return [
            'filename' => 'files/TestClass.php',
            'errors' => 1,
            'warnings' => 0,
            'fixable' => 0,
            'messages' => [
                3 => [
                    1 => [
                        [
                            'message' => 'PHP files must only contain PHP code.',
                            'source' => 'Generic.Files.InlineHTML.Found',
                            'severity' => 5,
                            'fixable' => false,
                            'type' => 'ERROR',
                        ],
                    ],
                ],
            ],
        ];
    }
}
