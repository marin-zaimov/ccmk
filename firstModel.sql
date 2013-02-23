SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `ccmk`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ccmk`.`User` ;

CREATE  TABLE IF NOT EXISTS `ccmk`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `firstName` VARCHAR(45) NULL ,
  `lastName` VARCHAR(45) NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `startDate` DATETIME NOT NULL ,
  `endDate` DATETIME NULL ,
  `lastLogin` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ccmk`.`Group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ccmk`.`Group` ;

CREATE  TABLE IF NOT EXISTS `ccmk`.`Group` (
  `id` INT NOT NULL ,
  `creator` INT NOT NULL ,
  `startDate` DATETIME NOT NULL ,
  `endDate` DATETIME NULL DEFAULT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ccmk`.`User_Group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ccmk`.`User_Group` ;

CREATE  TABLE IF NOT EXISTS `ccmk`.`User_Group` (
  `userId` INT NOT NULL ,
  `groupId` INT NOT NULL ,
  `startDate` DATETIME NOT NULL ,
  `endDate` DATETIME NULL DEFAULT NULL ,
  `invitedBy` INT NULL ,
  PRIMARY KEY (`userId`, `groupId`) ,
  INDEX `fk_user_group_user` (`userId` ASC) ,
  INDEX `fk_user_group_group` (`groupId` ASC) ,
  CONSTRAINT `fk_user_group_user`
    FOREIGN KEY (`userId` )
    REFERENCES `ccmk`.`User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_group_group`
    FOREIGN KEY (`groupId` )
    REFERENCES `ccmk`.`Group` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ccmk`.`Receipt`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ccmk`.`Receipt` ;

CREATE  TABLE IF NOT EXISTS `ccmk`.`Receipt` (
  `id` INT NOT NULL ,
  `amountDue` FLOAT NULL ,
  `userId` INT NULL ,
  `groupId` INT NULL ,
  `picture` VARCHAR(255) NULL ,
  `name` VARCHAR(45) NULL ,
  `status` ENUM('UNPAID','VOID','PAID') NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_receipt_group` (`groupId` ASC) ,
  INDEX `fk_receipt_user` (`userId` ASC) ,
  CONSTRAINT `fk_receipt_group`
    FOREIGN KEY (`groupId` )
    REFERENCES `ccmk`.`Group` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_receipt_user`
    FOREIGN KEY (`userId` )
    REFERENCES `ccmk`.`User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ccmk`.`Payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ccmk`.`Payment` ;

CREATE  TABLE IF NOT EXISTS `ccmk`.`Payment` (
  `id` INT NOT NULL ,
  `senderId` INT NOT NULL ,
  `receiverId` INT NOT NULL ,
  `startDate` DATETIME NOT NULL ,
  `endDate` DATETIME NULL DEFAULT NULL ,
  `amountDue` INT NOT NULL ,
  `receiptId` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_payment_receipt` (`receiptId` ASC) ,
  CONSTRAINT `fk_payment_receipt`
    FOREIGN KEY (`receiptId` )
    REFERENCES `ccmk`.`Receipt` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
