<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20260613161509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add images table and link projects.cover_image / skill_categories.icon to it';
    }

    public function up(Schema $schema): void
    {
        $images = $schema->createTable('images');
        $images->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $images->addColumn('image_name', Types::STRING, ['length' => 255, 'notnull' => false]);
        $images->addColumn('original_name', Types::STRING, ['length' => 255, 'notnull' => false]);
        $images->addColumn('mime_type', Types::STRING, ['length' => 255, 'notnull' => false]);
        $images->addColumn('size', Types::INTEGER, ['notnull' => false]);
        $images->addColumn('created_at', Types::DATETIME_IMMUTABLE);
        $images->addColumn('updated_at', Types::DATETIME_IMMUTABLE);
        $images->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );

        $projects = $schema->getTable('projects');
        $projects->addColumn('cover_image_id', Types::INTEGER, ['notnull' => false]);
        $projects->dropColumn('cover_image');
        $projects->addForeignKeyConstraint(
            'images',
            ['cover_image_id'],
            ['id'],
            ['onDelete' => 'SET NULL'],
            'FK_5C93B3A4E5A0E336',
        );
        $projects->addIndex(['cover_image_id'], 'IDX_5C93B3A4E5A0E336');

        $skillCategories = $schema->getTable('skill_categories');
        $skillCategories->addColumn('icon_id', Types::INTEGER, ['notnull' => false]);
        $skillCategories->dropColumn('icon');
        $skillCategories->addForeignKeyConstraint(
            'images',
            ['icon_id'],
            ['id'],
            ['onDelete' => 'SET NULL'],
            'FK_2F7A9A1A54B9D732',
        );
        $skillCategories->addIndex(['icon_id'], 'IDX_2F7A9A1A54B9D732');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('images');

        $projects = $schema->getTable('projects');
        $projects->dropForeignKey('FK_5C93B3A4E5A0E336');
        $projects->dropIndex('IDX_5C93B3A4E5A0E336');
        $projects->addColumn('cover_image', Types::STRING, ['length' => 255, 'notnull' => false]);
        $projects->dropColumn('cover_image_id');

        $skillCategories = $schema->getTable('skill_categories');
        $skillCategories->dropForeignKey('FK_2F7A9A1A54B9D732');
        $skillCategories->dropIndex('IDX_2F7A9A1A54B9D732');
        $skillCategories->addColumn('icon', Types::STRING, ['length' => 255, 'notnull' => false]);
        $skillCategories->dropColumn('icon_id');
    }
}
