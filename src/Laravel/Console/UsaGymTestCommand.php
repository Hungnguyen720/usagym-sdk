<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Laravel\Console;

use AustinW\UsaGym\Exceptions\AuthenticationException;
use AustinW\UsaGym\Exceptions\NotFoundException;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\UsaGym;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\password;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class UsaGymTestCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'usagym:test
        {--sanction= : Sanction ID for testing sanction-specific endpoints}
        {--skip-sanction : Skip sanction endpoint tests}';

    /**
     * @var string
     */
    protected $description = 'Test your USA Gymnastics API connection by reaching out to various endpoints';

    /**
     * @var array<string, array{status: string, message: string}>
     */
    private array $results = [];

    public function handle(): int
    {
        $this->displayWelcome();

        // Get credentials
        $credentials = $this->getCredentials();

        if ($credentials === null) {
            error('Cannot proceed without credentials.');

            return self::FAILURE;
        }

        // Create client with credentials
        $usagym = new UsaGym(
            username: $credentials['username'],
            password: $credentials['password'],
        );

        // Test authentication
        if (! $this->testAuthentication($usagym)) {
            $this->displaySummary();

            return self::FAILURE;
        }

        // Test disciplines
        $this->testDisciplines($usagym);

        // Test sanction endpoints
        if (! $this->option('skip-sanction')) {
            $this->testSanctionEndpoints($usagym);
        }

        // Display final summary
        $this->displaySummary();

        return $this->hasFailures() ? self::FAILURE : self::SUCCESS;
    }

    private function displayWelcome(): void
    {
        $this->newLine();
        info('USA Gymnastics API Connection Test');
        note('This command will test your API connection by reaching out to various endpoints.');
        $this->newLine();
    }

    /**
     * @return array{username: string, password: string}|null
     */
    private function getCredentials(): ?array
    {
        $username = config('usagym.username');
        $password = config('usagym.password');

        if (! empty($username) && ! empty($password)) {
            info("Using credentials from configuration (username: {$username})");

            return [
                'username' => $username,
                'password' => $password,
            ];
        }

        warning('Credentials not found in configuration.');
        note('Add USAGYM_USERNAME and USAGYM_PASSWORD to your .env file for persistent configuration.');
        $this->newLine();

        if (! confirm('Would you like to enter credentials manually?', true)) {
            return null;
        }

        $username = text(
            label: 'USA Gymnastics API Username',
            required: true,
        );

        $password = password(
            label: 'USA Gymnastics API Password',
            required: true,
        );

        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    private function testAuthentication(UsaGym $usagym): bool
    {
        $this->newLine();

        try {
            $result = spin(
                callback: fn () => $usagym->test(),
                message: 'Testing API credentials...',
            );

            if ($result) {
                $this->recordResult('Authentication', 'pass', 'Credentials are valid');
                info('Authentication successful!');

                return true;
            }

            $this->recordResult('Authentication', 'fail', 'Invalid credentials');
            error('Authentication failed. Please check your credentials.');

            return false;
        } catch (AuthenticationException $e) {
            $this->recordResult('Authentication', 'fail', 'Authentication error: '.$e->getMessage());
            error('Authentication failed: '.$e->getMessage());

            return false;
        } catch (UsaGymException $e) {
            $this->recordResult('Authentication', 'fail', 'API error: '.$e->getMessage());
            error('API error: '.$e->getMessage());

            return false;
        }
    }

    private function testDisciplines(UsaGym $usagym): void
    {
        $this->newLine();

        try {
            $disciplines = spin(
                callback: fn () => $usagym->disciplines()->all(),
                message: 'Fetching disciplines...',
            );

            $this->recordResult('Disciplines', 'pass', 'Retrieved '.count($disciplines).' disciplines');
            info('Retrieved '.count($disciplines).' disciplines:');

            $tableData = [];
            foreach ($disciplines as $discipline) {
                $tableData[] = [$discipline->code, $discipline->fullName];
            }

            table(['Code', 'Name'], $tableData);
        } catch (UsaGymException $e) {
            $this->recordResult('Disciplines', 'fail', 'Error: '.$e->getMessage());
            error('Failed to fetch disciplines: '.$e->getMessage());
        }
    }

    private function testSanctionEndpoints(UsaGym $usagym): void
    {
        $this->newLine();

        $sanctionId = $this->option('sanction');

        if (! $sanctionId) {
            if (! confirm('Would you like to test sanction-specific endpoints?', true)) {
                note('Skipping sanction endpoint tests.');

                return;
            }

            $sanctionId = text(
                label: 'Enter a Sanction ID to test with',
                placeholder: 'e.g., 58025',
                required: true,
                validate: fn (string $value) => is_numeric($value) ? null : 'Please enter a valid numeric sanction ID',
            );
        }

        $sanctionId = (int) $sanctionId;
        info("Testing sanction endpoints with Sanction ID: {$sanctionId}");
        $this->newLine();

        $sanction = $usagym->sanctions($sanctionId);

        // Test Clubs
        $this->testClubs($sanction);

        // Test Athletes
        $this->testAthletes($sanction);

        // Test Coaches
        $this->testCoaches($sanction);

        // Test Judges
        $this->testJudges($sanction);

        // Test Groups
        $this->testGroups($sanction);

        // Optional: Test Verification
        $this->testVerification($sanction);
    }

    private function testClubs(\AustinW\UsaGym\Resources\SanctionResource $sanction): void
    {
        try {
            $clubs = spin(
                callback: fn () => $sanction->reservations()->clubs(),
                message: 'Fetching club reservations...',
            );

            $count = count($clubs);
            $this->recordResult('Club Reservations', 'pass', "Retrieved {$count} clubs");
            info("Club Reservations: {$count} clubs found");

            if ($count > 0 && $count <= 10) {
                $tableData = [];
                foreach ($clubs as $club) {
                    $tableData[] = [$club->clubId, $club->displayName(), $club->location()];
                }
                table(['Club ID', 'Name', 'Location'], $tableData);
            } elseif ($count > 10) {
                note("Showing first 10 of {$count} clubs:");
                $tableData = [];
                foreach (array_slice($clubs, 0, 10) as $club) {
                    $tableData[] = [$club->clubId, $club->displayName(), $club->location()];
                }
                table(['Club ID', 'Name', 'Location'], $tableData);
            }
        } catch (NotFoundException $e) {
            $this->recordResult('Club Reservations', 'fail', 'Sanction not found');
            error('Sanction not found. Please verify the sanction ID.');
        } catch (UsaGymException $e) {
            $this->recordResult('Club Reservations', 'fail', 'Error: '.$e->getMessage());
            error('Failed to fetch clubs: '.$e->getMessage());
        }

        $this->newLine();
    }

    private function testAthletes(\AustinW\UsaGym\Resources\SanctionResource $sanction): void
    {
        try {
            $athletes = spin(
                callback: fn () => $sanction->reservations()->athletes(),
                message: 'Fetching athlete reservations...',
            );

            $count = count($athletes);
            $this->recordResult('Athlete Reservations', 'pass', "Retrieved {$count} athletes");
            info("Athlete Reservations: {$count} athletes found");

            if ($count > 0) {
                $sample = array_slice($athletes, 0, 5);
                note('Sample athletes (first 5):');
                $tableData = [];
                foreach ($sample as $athlete) {
                    $tableData[] = [
                        $athlete->memberId,
                        $athlete->fullName(),
                        $athlete->level ?? 'N/A',
                        $athlete->canCompete() ? 'Yes' : 'No',
                    ];
                }
                table(['Member ID', 'Name', 'Level', 'Can Compete'], $tableData);
            }
        } catch (UsaGymException $e) {
            $this->recordResult('Athlete Reservations', 'fail', 'Error: '.$e->getMessage());
            error('Failed to fetch athletes: '.$e->getMessage());
        }

        $this->newLine();
    }

    private function testCoaches(\AustinW\UsaGym\Resources\SanctionResource $sanction): void
    {
        try {
            $coaches = spin(
                callback: fn () => $sanction->reservations()->coaches(),
                message: 'Fetching coach reservations...',
            );

            $count = count($coaches);
            $this->recordResult('Coach Reservations', 'pass', "Retrieved {$count} coaches");
            info("Coach Reservations: {$count} coaches found");
        } catch (UsaGymException $e) {
            $this->recordResult('Coach Reservations', 'fail', 'Error: '.$e->getMessage());
            error('Failed to fetch coaches: '.$e->getMessage());
        }

        $this->newLine();
    }

    private function testJudges(\AustinW\UsaGym\Resources\SanctionResource $sanction): void
    {
        try {
            $judges = spin(
                callback: fn () => $sanction->reservations()->judges(),
                message: 'Fetching judge reservations...',
            );

            $count = count($judges);
            $this->recordResult('Judge Reservations', 'pass', "Retrieved {$count} judges");
            info("Judge Reservations: {$count} judges found");
        } catch (UsaGymException $e) {
            $this->recordResult('Judge Reservations', 'fail', 'Error: '.$e->getMessage());
            error('Failed to fetch judges: '.$e->getMessage());
        }

        $this->newLine();
    }

    private function testGroups(\AustinW\UsaGym\Resources\SanctionResource $sanction): void
    {
        try {
            $groups = spin(
                callback: fn () => $sanction->reservations()->groups(),
                message: 'Fetching group reservations...',
            );

            $count = count($groups);
            $this->recordResult('Group Reservations', 'pass', "Retrieved {$count} groups");
            info("Group Reservations: {$count} groups found");
        } catch (UsaGymException $e) {
            $this->recordResult('Group Reservations', 'fail', 'Error: '.$e->getMessage());
            error('Failed to fetch groups: '.$e->getMessage());
        }

        $this->newLine();
    }

    private function testVerification(\AustinW\UsaGym\Resources\SanctionResource $sanction): void
    {
        if (! confirm('Would you like to test athlete verification?', false)) {
            return;
        }

        $memberId = text(
            label: 'Enter a Member ID to verify',
            required: true,
        );

        try {
            $result = spin(
                callback: fn () => $sanction->verification()->athlete($memberId),
                message: 'Verifying athlete...',
            );

            if ($result === null) {
                $this->recordResult('Athlete Verification', 'warn', 'Member not found');
                warning("Member {$memberId} not found in verification response.");
            } else {
                $this->recordResult('Athlete Verification', 'pass', 'Verification completed');
                info('Verification Result:');
                table(
                    ['Field', 'Value'],
                    [
                        ['Member ID', $result->memberId],
                        ['Name', $result->fullName()],
                        ['Eligible', $result->eligible ? 'Yes' : 'No'],
                        ['Reason', $result->ineligibleReason ?? 'N/A'],
                    ]
                );
            }
        } catch (UsaGymException $e) {
            $this->recordResult('Athlete Verification', 'fail', 'Error: '.$e->getMessage());
            error('Failed to verify athlete: '.$e->getMessage());
        }

        $this->newLine();
    }

    private function recordResult(string $test, string $status, string $message): void
    {
        $this->results[$test] = [
            'status' => $status,
            'message' => $message,
        ];
    }

    private function displaySummary(): void
    {
        $this->newLine();
        info('Test Summary');
        $this->newLine();

        $tableData = [];
        foreach ($this->results as $test => $result) {
            $statusIcon = match ($result['status']) {
                'pass' => '<fg=green>PASS</>',
                'fail' => '<fg=red>FAIL</>',
                'warn' => '<fg=yellow>WARN</>',
                default => $result['status'],
            };

            $tableData[] = [$test, $statusIcon, $result['message']];
        }

        $this->table(['Test', 'Status', 'Details'], $tableData);

        $passed = count(array_filter($this->results, fn ($r) => $r['status'] === 'pass'));
        $failed = count(array_filter($this->results, fn ($r) => $r['status'] === 'fail'));
        $total = count($this->results);

        $this->newLine();

        if ($failed === 0) {
            info("All {$total} tests passed!");
        } else {
            warning("{$passed}/{$total} tests passed, {$failed} failed.");
        }
    }

    private function hasFailures(): bool
    {
        return count(array_filter($this->results, fn ($r) => $r['status'] === 'fail')) > 0;
    }
}
