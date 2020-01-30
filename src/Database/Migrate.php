<?php


namespace Bonnier\WP\ContentHub\Editor\Database;


use Bonnier\WP\ContentHub\Editor\Database\Migrations\CreateFeatureDatesTable;
use Bonnier\WP\ContentHub\Editor\Database\Migrations\Migration;

class Migrate
{
    const OPTION = 'contenthub-editor-migration';

    public static function run()
    {
        $dbVersion = intval(get_option(self::OPTION) ?: 0);
        $migrations = collect([
            CreateFeatureDatesTable::class
        ]);

        if ($dbVersion >= $migrations->count()) {
            return;
        }

        $migrations->each(function (string $migration, int $index) use ($dbVersion) {
            $migrationReflection = new \ReflectionClass($migration);
            if (!$migrationReflection->implementsInterface(Migration::class)) {
                throw new \Exception(
                    sprintf('The migration \'%s\' does not implement the Migration interface', $migration)
                );
            }
            if ($index < $dbVersion) {
                return;
            }
            /** @var Migration $migration */
            $migration::migrate();
            if ($migration::verify()) {
                update_option(self::OPTION, $index + 1);
            } else {
                throw new \Exception(sprintf('An error occured running the migration \'%s\'', $migration));
            }
        });
    }
}