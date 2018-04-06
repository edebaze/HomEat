<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180327105833 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE orders_review');
        $this->addSql('DROP TABLE recipes_categories_recipes');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B55C7CF1F2 FOREIGN KEY (categories_recipes_id) REFERENCES categories_recipes (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B55C7CF1F2 ON recipes (categories_recipes_id)');
        $this->addSql('ALTER TABLE review ADD user_id INT DEFAULT NULL, ADD destinataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A4F84F6E ON review (destinataire_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE orders_review (orders_id INT NOT NULL, review_id INT NOT NULL, INDEX IDX_D8D3B817CFFE9AD6 (orders_id), INDEX IDX_D8D3B8173E2E969B (review_id), PRIMARY KEY(orders_id, review_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes_categories_recipes (recipes_id INT NOT NULL, categories_recipes_id INT NOT NULL, INDEX IDX_86D5D8D5FDF2B1FA (recipes_id), INDEX IDX_86D5D8D55C7CF1F2 (categories_recipes_id), PRIMARY KEY(recipes_id, categories_recipes_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders_review ADD CONSTRAINT FK_D8D3B8173E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_review ADD CONSTRAINT FK_D8D3B817CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_categories_recipes ADD CONSTRAINT FK_86D5D8D55C7CF1F2 FOREIGN KEY (categories_recipes_id) REFERENCES categories_recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_categories_recipes ADD CONSTRAINT FK_86D5D8D5FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B55C7CF1F2');
        $this->addSql('DROP INDEX IDX_A369E2B55C7CF1F2 ON recipes');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A4F84F6E');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('DROP INDEX IDX_794381C6A4F84F6E ON review');
        $this->addSql('ALTER TABLE review DROP user_id, DROP destinataire_id');
    }
}
