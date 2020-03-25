DROP DATABASE IF EXISTS pa2a2drivncook;

CREATE DATABASE pa2a2drivncook;
USE pa2a2drivncook;

CREATE TABLE SITEROLE(
    idRole INTEGER PRIMARY KEY AUTO_INCREMENT,
    roleName VARCHAR(60)
);
CREATE TABLE FIDELITY(
    idFidelity INTEGER PRIMARY KEY AUTO_INCREMENT,
    points INTEGER DEFAULT 0
);
CREATE TABLE USER(
    idUser INTEGER PRIMARY KEY AUTO_INCREMENT,
    lastname VARCHAR(100),
    firstname VARCHAR(100),
    emailAddress VARCHAR(200),
    phoneNumber CHAR(10),
    token CHAR(60),
    createDate DATE DEFAULT CURRENT_DATE,
    userRole INTEGER,
    FOREIGN KEY (userRole) REFERENCES SITEROLE(idRole),
    fidelityCard INTEGER,
    FOREIGN KEY (fidelityCard) REFERENCES FIDELITY(idFidelity)
    
);
CREATE TABLE STATUS(
    idStatus INTEGER PRIMARY KEY AUTO_INCREMENT,
    statusName VARCHAR(60),
    statusDescription VARCHAR(60),
    statusType VARCHAR(60),
    updateDate DATE DEFAULT CURRENT_DATE
);
CREATE TABLE EVENTS(
    idEvent INTEGER PRIMARY KEY AUTO_INCREMENT,
    eventType VARCHAR(60),
    eventName VARCHAR(60),
    eventAddress VARCHAR(100),
    eventCity VARCHAR(60),
    eventPostalCode VARCHAR(6),
    eventBeginDate DATE,
    eventEndDate DATE,
    eventStartHour TIME,
    eventEndHour TIME
);
CREATE TABLE WAREHOUSES(
    idWarehouse INTEGER PRIMARY KEY AUTO_INCREMENT,
    warehouseName VARCHAR(60),
    warehouseCity VARCHAR(60),
    warehouseAddress VARCHAR(100),
    warehousePostalCode VARCHAR(6)
);
CREATE TABLE OPENDAYS(
    idOpen INTEGER PRIMARY KEY AUTO_INCREMENT,
    openDays VARCHAR(60),
    startHour TIME,
    endHour TIME
);
CREATE TABLE TRUCK(
    idTruck INTEGER PRIMARY KEY AUTO_INCREMENT,
    truckManufacturers VARCHAR(100),
    truckModel VARCHAR(100),
    licensePlate VARCHAR(8),
    warehouse INTEGER NOT NULL,
    FOREIGN KEY (warehouse) REFERENCES WAREHOUSES(idWarehouse),
    user INTEGER,
    FOREIGN KEY (user) REFERENCES USER(idUser),
    status INTEGER NOT NULL,
    FOREIGN KEY (status) REFERENCES STATUS(idStatus),
    planning INTEGER NOT NULL,
    FOREIGN KEY (planning) REFERENCES OPENDAYS(idOpen)
);
CREATE TABLE HOST(
    event INTEGER NOT NULL,
    truck INTEGER NOT NULL,
    PRIMARY KEY(event, truck),
    FOREIGN KEY (event) REFERENCES EVENTS(idEvent),
    FOREIGN KEY (truck) REFERENCES TRUCK(idTruck)
);
CREATE TABLE MAINTENANCE(
    idMaintenance INTEGER PRIMARY KEY AUTO_INCREMENT,
    maintenanceName VARCHAR(60),
    maintenancePrice INTEGER,
    maintenanceDate DATE,
    truck INTEGER NOT NULL,
    FOREIGN KEY (truck) REFERENCES TRUCK(idTruck)
);
CREATE TABLE ORDERS(
    idOrder INTEGER PRIMARY KEY AUTO_INCREMENT,
    orderPrice INTEGER,
    orderDate DATE DEFAULT CURRENT_DATE,
    truck INTEGER NOT NULL,
    FOREIGN KEY (truck) REFERENCES TRUCK(idTruck),
    user INTEGER NOT NULL,
    FOREIGN KEY (user) REFERENCES USER(idUser),
    status INTEGER NOT NULL,
    FOREIGN KEY (status) REFERENCES STATUS(idStatus)
);
CREATE TABLE MENUS(
    idMenu INTEGER PRIMARY KEY AUTO_INCREMENT,
    menuName VARCHAR(60),
    menuPrice INTEGER,
    menuQuantity INTEGER,
    truck INTEGER,
    FOREIGN KEY (truck) REFERENCES TRUCK(idTruck)
);
CREATE TABLE INGREDIENTS(
    idIngredient INTEGER PRIMARY KEY AUTO_INCREMENT,
    ingredientName VARCHAR(60),
    ingredientQuatity INTEGER,
    ingredientPrice INTEGER
);
CREATE TABLE STORE(
    warehouse INTEGER NOT NULL,
    ingredient INTEGER NOT NULL,
    PRIMARY KEY(warehouse, ingredient),
    FOREIGN KEY (warehouse) REFERENCES WAREHOUSES(idWarehouse),
    FOREIGN KEY (ingredient) REFERENCES INGREDIENTS(idIngredient)
);
CREATE TABLE PRODUCTS(
    idProduct INTEGER PRIMARY KEY AUTO_INCREMENT,
    productName VARCHAR(60),
    productPrice INTEGER,
    productQuantity INTEGER,
    productOrigin VARCHAR(60),
    truck INTEGER NOT NULL,
    FOREIGN KEY (truck) REFERENCES TRUCK(idTruck)
);
CREATE TABLE REQUIRED(
    menu INTEGER NOT NULL,
    product INTEGER NOT NULL,
    PRIMARY KEY(menu, product),
    FOREIGN KEY (menu) REFERENCES MENUS(idMenu),
    FOREIGN KEY (product) REFERENCES PRODUCTS(idProduct)
);
CREATE TABLE USED(
    ingredient INTEGER NOT NULL,
    product INTEGER NOT NULL,
    PRIMARY KEY(ingredient, product),
    FOREIGN KEY (ingredient) REFERENCES INGREDIENTS(idIngredient),
    FOREIGN KEY (product) REFERENCES PRODUCTS(idProduct)
);
CREATE TABLE ORDERMENU(
    orderNumber INTEGER NOT NULL,
    menu INTEGER NOT NULL,
    PRIMARY KEY(orderNumber, menu),
    FOREIGN KEY (orderNumber) REFERENCES ORDERS(idOrder),
    FOREIGN KEY (menu) REFERENCES MENUS(idMenu)
);
CREATE TABLE ORDERPRODUCT(
    orderNumber INTEGER NOT NULL,
    product INTEGER NOT NULL,
    PRIMARY KEY(orderNumber, product),
    FOREIGN KEY (orderNumber) REFERENCES ORDERS(idOrder),
    FOREIGN KEY (product) REFERENCES PRODUCTS(idProduct)
);
