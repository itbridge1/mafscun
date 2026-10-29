<?php

namespace Tests\Unit;

use Illuminate\Container\Container;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\TestCase;

class EventQuestionsMigrationTest extends TestCase
{
    public function test_mysql_constraint_names_fit_and_participant_answers_keep_cascade_deletion(): void
    {
        $previousApp = Facade::getFacadeApplication();
        Facade::setFacadeApplication(new Container());
        Schema::swap(Mockery::mock(\Illuminate\Database\Schema\Builder::class));
        $statements = [];
        $compile = function ($table, $callback) use (&$statements) {
            $blueprint = new Blueprint($table);
            $blueprint->create();
            $callback($blueprint);
            $connection = new MySqlConnection(null, 'migration_test');
            $statements = array_merge($statements, $blueprint->toSql($connection, new MySqlGrammar()));
        };

        try {
            Schema::shouldReceive('create')->once()->with('event_questions', Mockery::type('Closure'))->andReturnUsing($compile);
            Schema::shouldReceive('create')->once()->with('event_participant_answers', Mockery::type('Closure'))->andReturnUsing($compile);
            Schema::shouldReceive('hasColumn')->once()->with('event_booking_participants', 'custom_answers')->andReturn(true);

            $migration = require __DIR__ . '/../../database/migrations/2026_09_21_120000_create_event_questions_and_answers_tables.php';
            $migration->up();

            $sql = implode("\n", $statements);
            preg_match_all('/(?:constraint|index) `([^`]+)`/i', $sql, $matches);
            $this->assertNotEmpty($matches[1]);
            foreach ($matches[1] as $identifier) {
                $this->assertLessThanOrEqual(64, strlen($identifier), $identifier);
            }
            $this->assertStringContainsString(
                'foreign key (`event_booking_participant_id`) references `event_booking_participants` (`id`) on delete cascade',
                $sql
            );
        } finally {
            Mockery::close();
            Facade::clearResolvedInstances();
            Facade::setFacadeApplication($previousApp);
        }
    }
}
