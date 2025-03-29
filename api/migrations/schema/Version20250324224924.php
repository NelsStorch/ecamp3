<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324224924 extends AbstractMigration {
    public function getDescription(): string {
        return 'ContentNode.RootId not nullable; Index on ContentNode.Strategy';
    }

    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_node ALTER rootId SET NOT NULL');
        $this->addSql('CREATE INDEX IDX_481D0580144645ED ON content_node (strategy)');
    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_481D0580144645ED');
        $this->addSql('ALTER TABLE content_node ALTER rootid DROP NOT NULL');
    }
}
