<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20150125122954 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE t3dd_backend_domain_model_mate (persistence_object_identifier VARCHAR(40) NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE netlogix_crud_domain_model_datatransfer_abstractdatatrans_3c9fe (persistence_object_identifier VARCHAR(40) NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE t3dd_backend_domain_model_datatransfer_participant (persistence_object_identifier VARCHAR(40) NOT NULL, innermostself VARCHAR(40) DEFAULT NULL, UNIQUE INDEX UNIQ_CBC263B841B3E602 (innermostself), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE t3dd_backend_domain_model_participant (persistence_object_identifier VARCHAR(40) NOT NULL, date DATETIME NOT NULL, name VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, foottype VARCHAR(255) NOT NULL, footwishes VARCHAR(255) NOT NULL, tshirtsize VARCHAR(255) NOT NULL, noob TINYINT(1) NOT NULL, room TINYINT(1) NOT NULL, roomsize INT NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE t3dd_backend_domain_model_participant_roommates_join (backend_participant VARCHAR(40) NOT NULL, backend_mate VARCHAR(40) NOT NULL, INDEX IDX_821604EA840E0F2A (backend_participant), INDEX IDX_821604EA5676F6D7 (backend_mate), PRIMARY KEY(backend_participant, backend_mate)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_datatransfer_participant ADD CONSTRAINT FK_CBC263B841B3E602 FOREIGN KEY (innermostself) REFERENCES t3dd_backend_domain_model_participant (persistence_object_identifier)");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_datatransfer_participant ADD CONSTRAINT FK_CBC263B847A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES netlogix_crud_domain_model_datatransfer_abstractdatatrans_3c9fe (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_participant_roommates_join ADD CONSTRAINT FK_821604EA840E0F2A FOREIGN KEY (backend_participant) REFERENCES t3dd_backend_domain_model_participant (persistence_object_identifier)");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_participant_roommates_join ADD CONSTRAINT FK_821604EA5676F6D7 FOREIGN KEY (backend_mate) REFERENCES t3dd_backend_domain_model_mate (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_participant_roommates_join DROP FOREIGN KEY FK_821604EA5676F6D7");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_datatransfer_participant DROP FOREIGN KEY FK_CBC263B847A46B0A");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_datatransfer_participant DROP FOREIGN KEY FK_CBC263B841B3E602");
		$this->addSql("ALTER TABLE t3dd_backend_domain_model_participant_roommates_join DROP FOREIGN KEY FK_821604EA840E0F2A");
		$this->addSql("DROP TABLE t3dd_backend_domain_model_mate");
		$this->addSql("DROP TABLE netlogix_crud_domain_model_datatransfer_abstractdatatrans_3c9fe");
		$this->addSql("DROP TABLE t3dd_backend_domain_model_datatransfer_participant");
		$this->addSql("DROP TABLE t3dd_backend_domain_model_participant");
		$this->addSql("DROP TABLE t3dd_backend_domain_model_participant_roommates_join");
	}
}