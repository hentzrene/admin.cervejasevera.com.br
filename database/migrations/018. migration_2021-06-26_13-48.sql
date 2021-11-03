ALTER TABLE
  `images`
ADD
  COLUMN `order` INT NOT NULL DEFAULT '0'
AFTER
  `title`;
