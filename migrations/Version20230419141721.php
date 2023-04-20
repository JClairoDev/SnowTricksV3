<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230419141721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary ADD trick_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_1CAC12CAB46B9EE8 ON commentary (trick_id_id)');
        $this->addSql('ALTER TABLE media ADD trick_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CB46B9EE8 ON media (trick_id_id)');
        $this->addSql('ALTER TABLE trick ADD category_id_id INT NOT NULL, ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9777D11E ON trick (category_id_id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9D86650F ON trick (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAB46B9EE8');
        $this->addSql('DROP INDEX IDX_1CAC12CAB46B9EE8 ON commentary');
        $this->addSql('ALTER TABLE commentary DROP trick_id_id');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CB46B9EE8');
        $this->addSql('DROP INDEX IDX_6A2CA10CB46B9EE8 ON media');
        $this->addSql('ALTER TABLE media DROP trick_id_id');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9777D11E');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9D86650F');
        $this->addSql('DROP INDEX IDX_D8F0A91E9777D11E ON trick');
        $this->addSql('DROP INDEX IDX_D8F0A91E9D86650F ON trick');
        $this->addSql('ALTER TABLE trick DROP category_id_id, DROP user_id_id');
    }
}
