CREATE TABLE IF NOT EXISTS `ipol_demo_orders`
(
    `ID`                        INT(11) NOT NULL auto_increment,
    `BITRIX_ID`                 INT(11) NOT NULL,
    `DEMO_ID`		        VARCHAR(14),                  -- Generated barcode, for compatibility reasons
    `DEMO_GUID`		        VARCHAR(36),                  -- /v3/orders orderId
    `BARK_GENERATE_BY_SERVER`   CHAR(1) NOT NULL DEFAULT 'N', -- Barcode generation flag
    `STATUS`                    VARCHAR(10),
    `DEMO_STATUS`           VARCHAR(11),
    `DEMO_EXECUTION_STATUS` VARCHAR(50),
    `BRAND_NAME`		        text,
    `CLIENT_NAME`               text NOT NULL,
    `CLIENT_EMAIL`              text,
    `CLIENT_PHONE` 	            VARCHAR(12) NOT NULL,
    `PLANNED_RECEIVE_DATE`      DATETIME,
    `SHIPMENT_DATE`             DATETIME,
    `RECEIVER_LOCATION`         CHAR(36) NOT NULL,
    `SENDER_LOCATION`	        text NOT NULL,
    `UNDELIVERABLE_OPTION`      VARCHAR(11) NOT NULL,
    `CARGOES`                   text NOT NULL,
    `CURRENCY`                  VARCHAR(3),
    `DELIVERY_COST`             DECIMAL(18,4),
    `DELIVERY_COST_CURRENCY`    VARCHAR(3),
    `PAYMENT_VALUE`             DECIMAL(18,4) NOT NULL,
    `PAYMENT_TYPE`              VARCHAR(10) NOT NULL,
    `PAYMENT_CURRENCY`          VARCHAR(3) NOT NULL,
    `PREPAYMENT`                DECIMAL(18,4),
    `PREPAYMENT_CURRENCY`       VARCHAR(3),
    `PRICE`                     DECIMAL(18,4) NOT NULL,
    `PRICE_CURRENCY`            VARCHAR(3) NOT NULL,
    `MESSAGE`                   text,
    `OK`                        CHAR(1),
    `UPTIME`                    VARCHAR(10),
    PRIMARY KEY (`ID`),
    INDEX IX_IPOL_DEMO_ORDERS_BITRIX_ID (`BITRIX_ID`),
    INDEX IX_IPOL_DEMO_ORDERS_DEMO_ID (`DEMO_ID`)
);