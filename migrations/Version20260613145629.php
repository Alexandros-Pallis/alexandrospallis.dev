<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20260613145629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create portfolio schema (projects, tags, skills, timeline entries, '
            . 'credentials, users) and the Doctrine messenger transport table';
    }

    public function up(Schema $schema): void
    {
        $credentials = $schema->createTable('credentials');
        $credentials->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $credentials->addColumn('title', Types::STRING, ['length' => 255]);
        $credentials->addColumn('issuer', Types::STRING, ['length' => 255]);
        $credentials->addColumn('year', Types::SMALLINT);
        $credentials->addColumn('type', Types::STRING, ['length' => 255]);
        $credentials->addColumn('icon', Types::STRING, ['length' => 255, 'notnull' => false]);
        $credentials->addColumn('display_order', Types::INTEGER);
        $credentials->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );

        $projects = $schema->createTable('projects');
        $projects->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $projects->addColumn('title', Types::STRING, ['length' => 255]);
        $projects->addColumn('slug', Types::STRING, ['length' => 255]);
        $projects->addColumn('summary', Types::TEXT);
        $projects->addColumn('cover_image', Types::STRING, ['length' => 255, 'notnull' => false]);
        $projects->addColumn('status', Types::STRING, ['length' => 255]);
        $projects->addColumn('category', Types::STRING, ['length' => 255]);
        $projects->addColumn('external_url', Types::STRING, ['length' => 255, 'notnull' => false]);
        $projects->addColumn('repo_url', Types::STRING, ['length' => 255, 'notnull' => false]);
        $projects->addColumn('is_featured', Types::BOOLEAN);
        $projects->addColumn('display_order', Types::INTEGER);
        $projects->addColumn('created_at', Types::DATETIME_IMMUTABLE);
        $projects->addColumn('updated_at', Types::DATETIME_IMMUTABLE);
        $projects->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );
        $projects->addUniqueIndex(['slug'], 'UNIQ_5C93B3A4989D9B62');

        $projectTag = $schema->createTable('project_tag');
        $projectTag->addColumn('project_id', Types::INTEGER);
        $projectTag->addColumn('tag_id', Types::INTEGER);
        $projectTag->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('project_id', 'tag_id')->create(),
        );
        $projectTag->addIndex(['project_id'], 'IDX_91F26D60166D1F9C');
        $projectTag->addIndex(['tag_id'], 'IDX_91F26D60BAD26311');

        $skillCategories = $schema->createTable('skill_categories');
        $skillCategories->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $skillCategories->addColumn('name', Types::STRING, ['length' => 255]);
        $skillCategories->addColumn('subtitle', Types::STRING, ['length' => 255, 'notnull' => false]);
        $skillCategories->addColumn('icon', Types::STRING, ['length' => 255, 'notnull' => false]);
        $skillCategories->addColumn('display_order', Types::INTEGER);
        $skillCategories->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );

        $skills = $schema->createTable('skills');
        $skills->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $skills->addColumn('name', Types::STRING, ['length' => 255]);
        $skills->addColumn('proficiency', Types::SMALLINT);
        $skills->addColumn('display_order', Types::INTEGER);
        $skills->addColumn('category_id', Types::INTEGER);
        $skills->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );
        $skills->addIndex(['category_id'], 'IDX_D531167012469DE2');

        $tags = $schema->createTable('tags');
        $tags->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $tags->addColumn('name', Types::STRING, ['length' => 255]);
        $tags->addColumn('slug', Types::STRING, ['length' => 255]);
        $tags->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );
        $tags->addUniqueIndex(['name'], 'UNIQ_6FBC94265E237E06');
        $tags->addUniqueIndex(['slug'], 'UNIQ_6FBC9426989D9B62');

        $timelineEntries = $schema->createTable('timeline_entries');
        $timelineEntries->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $timelineEntries->addColumn('phase_label', Types::STRING, ['length' => 255]);
        $timelineEntries->addColumn('title', Types::STRING, ['length' => 255]);
        $timelineEntries->addColumn('description', Types::TEXT);
        $timelineEntries->addColumn('is_current', Types::BOOLEAN);
        $timelineEntries->addColumn('display_order', Types::INTEGER);
        $timelineEntries->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );

        $timelineEntryTag = $schema->createTable('timeline_entry_tag');
        $timelineEntryTag->addColumn('timeline_entry_id', Types::INTEGER);
        $timelineEntryTag->addColumn('tag_id', Types::INTEGER);
        $timelineEntryTag->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('timeline_entry_id', 'tag_id')->create(),
        );
        $timelineEntryTag->addIndex(['timeline_entry_id'], 'IDX_67B72036EA061249');
        $timelineEntryTag->addIndex(['tag_id'], 'IDX_67B72036BAD26311');

        $users = $schema->createTable('users');
        $users->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $users->addColumn('email', Types::STRING, ['length' => 180]);
        $users->addColumn('password', Types::STRING, ['length' => 255]);
        $users->addColumn('roles', Types::JSON);
        $users->addColumn('name', Types::STRING, ['length' => 255]);
        $users->addColumn('created_at', Types::DATETIME_IMMUTABLE);
        $users->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );
        $users->addUniqueIndex(['email'], 'UNIQ_1483A5E9E7927C74');

        $messengerMessages = $schema->createTable('messenger_messages');
        $messengerMessages->addColumn('id', Types::BIGINT, ['autoincrement' => true]);
        $messengerMessages->addColumn('body', Types::TEXT);
        $messengerMessages->addColumn('headers', Types::TEXT);
        $messengerMessages->addColumn('queue_name', Types::STRING, ['length' => 190]);
        $messengerMessages->addColumn('created_at', Types::DATETIME_MUTABLE);
        $messengerMessages->addColumn('available_at', Types::DATETIME_MUTABLE);
        $messengerMessages->addColumn('delivered_at', Types::DATETIME_MUTABLE, ['notnull' => false]);
        $messengerMessages->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create(),
        );
        $messengerMessages->addIndex(
            ['queue_name', 'available_at', 'delivered_at', 'id'],
            'IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750',
        );

        $projectTag->addForeignKeyConstraint(
            'projects',
            ['project_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_91F26D60166D1F9C',
        );
        $projectTag->addForeignKeyConstraint(
            'tags',
            ['tag_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_91F26D60BAD26311',
        );
        $skills->addForeignKeyConstraint(
            'skill_categories',
            ['category_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_D531167012469DE2',
        );
        $timelineEntryTag->addForeignKeyConstraint(
            'timeline_entries',
            ['timeline_entry_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_67B72036EA061249',
        );
        $timelineEntryTag->addForeignKeyConstraint(
            'tags',
            ['tag_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_67B72036BAD26311',
        );
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('project_tag')->dropForeignKey('FK_91F26D60166D1F9C');
        $schema->getTable('project_tag')->dropForeignKey('FK_91F26D60BAD26311');
        $schema->getTable('skills')->dropForeignKey('FK_D531167012469DE2');
        $schema->getTable('timeline_entry_tag')->dropForeignKey('FK_67B72036EA061249');
        $schema->getTable('timeline_entry_tag')->dropForeignKey('FK_67B72036BAD26311');

        $schema->dropTable('credentials');
        $schema->dropTable('projects');
        $schema->dropTable('project_tag');
        $schema->dropTable('skill_categories');
        $schema->dropTable('skills');
        $schema->dropTable('tags');
        $schema->dropTable('timeline_entries');
        $schema->dropTable('timeline_entry_tag');
        $schema->dropTable('users');
        $schema->dropTable('messenger_messages');
    }
}
