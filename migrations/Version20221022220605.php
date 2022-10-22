<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221022220605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fridge_ingredient (fridge_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_44D72B8514A48E59 (fridge_id), INDEX IDX_44D72B85933FE08C (ingredient_id), PRIMARY KEY(fridge_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, kcal_for100g VARCHAR(255) NOT NULL, price_for100g VARCHAR(255) NOT NULL, INDEX IDX_6BAF7870C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_ingredient (recipe_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_22D1FE1359D8A214 (recipe_id), INDEX IDX_22D1FE13933FE08C (ingredient_id), PRIMARY KEY(recipe_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fridge_ingredient ADD CONSTRAINT FK_44D72B8514A48E59 FOREIGN KEY (fridge_id) REFERENCES fridge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fridge_ingredient ADD CONSTRAINT FK_44D72B85933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870C54C8C93 FOREIGN KEY (type_id) REFERENCES ingredient_categorie (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fridge ADD id INT AUTO_INCREMENT NOT NULL, ADD owner_id INT DEFAULT NULL, DROP fridge, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F2E94D897E3C61F9 ON fridge (owner_id)');
        $this->addSql('ALTER TABLE recipe ADD author_id INT DEFAULT NULL, DROP ingredients, DROP author');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DA88B137F675F31B ON recipe (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fridge_ingredient DROP FOREIGN KEY FK_44D72B8514A48E59');
        $this->addSql('ALTER TABLE fridge_ingredient DROP FOREIGN KEY FK_44D72B85933FE08C');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870C54C8C93');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13933FE08C');
        $this->addSql('DROP TABLE fridge_ingredient');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_categorie');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('ALTER TABLE fridge MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE fridge DROP FOREIGN KEY FK_F2E94D897E3C61F9');
        $this->addSql('DROP INDEX UNIQ_F2E94D897E3C61F9 ON fridge');
        $this->addSql('DROP INDEX `primary` ON fridge');
        $this->addSql('ALTER TABLE fridge ADD fridge INT NOT NULL, DROP id, DROP owner_id');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137F675F31B');
        $this->addSql('DROP INDEX IDX_DA88B137F675F31B ON recipe');
        $this->addSql('ALTER TABLE recipe ADD ingredients JSON NOT NULL, ADD author VARCHAR(255) NOT NULL, DROP author_id');
    }
}
