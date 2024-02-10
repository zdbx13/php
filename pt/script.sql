DROP DATABASE ptdb;
-- Create user
CREATE USER "martiusr"@"localhost" IDENTIFIED BY "martiPassword";

-- Create database
CREATE DATABASE ptdb
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

-- Give user privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON ptdb.* TO "martiusr"@"localhost";

-- Create tables
USE ptdb;

-- User table
CREATE TABLE users (
   id INTEGER PRIMARY KEY AUTO_INCREMENT,
   username VARCHAR(50) NOT NULL UNIQUE,
   password VARCHAR(50) NOT NULL,
   role VARCHAR(20) DEFAULT 'registered',
   email VARCHAR(100) NOT NULL,
   dob DATE NOT NULL
) ENGINE=InnoDb;

-- Products table
CREATE TABLE products (
   id INTEGER PRIMARY KEY AUTO_INCREMENT,
   code VARCHAR (10) UNIQUE,
   description VARCHAR(100) NOT NULL,
   price FLOAT
) ENGINE=InnoDb;

-- Orders table
CREATE TABLE orders (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  creationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  delMethod VARCHAR(50) NOT NULL,
  customer INTEGER references users(id) ON DELETE CASCADE ON UPDATE cascade
) ENGINE=InnoDb;

-- Order-products table
CREATE TABLE orderproducts (
  orderId INTEGER NOT NULL references orders(id) ON DELETE cascade ON UPDATE cascade,
  productId INTEGER NOT NULL references products(id) ON DELETE CASCADE ON UPDATE cascade,
  quantity INTEGER NOT NULL,
  unitPrice FLOAT NOT NULL,
  PRIMARY KEY (orderId, productId)
) ENGINE=InnoDb;


-- Inserts
-- Users data
INSERT INTO users (username, password,role,email,dob) VALUES 
    ('user1', 'pass1','admin','user1@proven.cat','2000-01-01'),
    ('user2', 'pass2','registered','user2@proven.cat','2000-02-02'),
    ('user3', 'pass3','admin','user3@proven.cat','2000-03-03'),
    ('user4', 'pass4','registered','user4@proven.cat','2000-04-04');

-- Products data
INSERT INTO products (code, description,price) VALUES 
    ('P1', 'product 1',11.1),
    ('P2', 'product 2',22.2),
    ('P3', 'product 3',33.3),
    ('P4', 'product 4',33.3),
    ('P5', 'product 5',33.3),
    ('P6', 'product 6',33.3),
    ('P7', 'product 7',33.3),
    ('P8', 'product 8',44.4);  

-- Orders data
INSERT INTO orders(delMethod, customer) VALUES
('Click & Collect', 4);

-- Order-products data
INSERT INTO orderproducts(orderId, productId, quantity, unitPrice) VALUES
 (1, 1, 1, 11.1),
 (1, 2, 2, 44.4),
 (1, 3, 3, 99.9);