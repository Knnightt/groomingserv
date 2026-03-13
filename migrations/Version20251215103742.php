<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251215103742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent LONGTEXT DEFAULT NULL, route VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_FD06F647A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, status VARCHAR(20) NOT NULL, notes LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, groomer_notes VARCHAR(50) DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, tax DOUBLE PRECISION DEFAULT NULL, is_paid TINYINT NOT NULL, payment_method VARCHAR(50) DEFAULT NULL, payment_date DATETIME DEFAULT NULL, reminder_sent TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, customer_id INT NOT NULL, pet_id INT NOT NULL, service_id INT NOT NULL, assigned_staff_id INT DEFAULT NULL, INDEX IDX_FE38F8449395C3F3 (customer_id), INDEX IDX_FE38F844966F7FB6 (pet_id), INDEX IDX_FE38F844ED5CA9E6 (service_id), INDEX IDX_FE38F844704051C4 (assigned_staff_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, species VARCHAR(50) NOT NULL, breed VARCHAR(100) DEFAULT NULL, age INT DEFAULT NULL, gender VARCHAR(10) DEFAULT NULL, medical_notes LONGTEXT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, life_stage VARCHAR(50) DEFAULT NULL, is_neutered TINYINT NOT NULL, is_vaccinated TINYINT NOT NULL, coat_type VARCHAR(50) DEFAULT NULL, temperament VARCHAR(50) DEFAULT NULL, allergies LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT NOT NULL, owner_id INT NOT NULL, INDEX IDX_E4529B857E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, duration INT NOT NULL, is_active TINYINT NOT NULL, category VARCHAR(50) DEFAULT NULL, features LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, requires_special_equipment TINYINT NOT NULL, special_instructions LONGTEXT DEFAULT NULL, min_pet_age INT DEFAULT NULL, max_pet_age INT DEFAULT NULL, weight_limit DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE staff (id INT AUTO_INCREMENT NOT NULL, staff_id VARCHAR(50) NOT NULL, staff_role VARCHAR(50) NOT NULL, specializations JSON NOT NULL, biography LONGTEXT DEFAULT NULL, experience_years INT DEFAULT NULL, hourly_rate NUMERIC(10, 2) NOT NULL, hire_date DATETIME NOT NULL, termination_date DATETIME DEFAULT NULL, employment_status VARCHAR(20) NOT NULL, can_handle_aggressive_pets TINYINT NOT NULL, is_certified TINYINT NOT NULL, certifications VARCHAR(255) DEFAULT NULL, working_days LONGTEXT DEFAULT NULL, start_time TIME DEFAULT NULL, end_time TIME DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_426EF392A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, plan VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, amount NUMERIC(10, 2) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, renewal_date DATETIME DEFAULT NULL, stripe_customer_id VARCHAR(255) DEFAULT NULL, stripe_subscription_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_A3C664D3A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, reset_token_expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(100) NOT NULL, bio LONGTEXT DEFAULT NULL, phone_number VARCHAR(20) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, state VARCHAR(100) DEFAULT NULL, zip_code VARCHAR(20) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, gender VARCHAR(10) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_D95AB405A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8449395C3F3 FOREIGN KEY (customer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844966F7FB6 FOREIGN KEY (pet_id) REFERENCES pet (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844704051C4 FOREIGN KEY (assigned_staff_id) REFERENCES staff (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B857E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF392A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647A76ED395');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8449395C3F3');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844966F7FB6');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844ED5CA9E6');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844704051C4');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B857E3C61F9');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF392A76ED395');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('DROP TABLE activity_log');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE pet');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE staff');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
