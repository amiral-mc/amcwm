ALTER TABLE  `tenders` CHANGE  `technical_date`  `technical_date` DATETIME NULL DEFAULT NULL ,
CHANGE  `financial_date`  `financial_date` DATETIME NULL DEFAULT NULL ,
CHANGE  `rfp_price1`  `rfp_price1` DECIMAL( 10, 2 ) NULL DEFAULT NULL ,
CHANGE  `primary_insurance`  `primary_insurance` DECIMAL( 10, 2 ) NULL DEFAULT NULL;

alter table `tenders`
  add `rfp_price1_currency` CHAR(3) NOT NULL DEFAULT 'EGP',
  add `rfp_price2_currency` CHAR(3) NOT NULL DEFAULT 'EGP',
  add `primary_insurance_currency` CHAR(3) NOT NULL DEFAULT 'EGP';