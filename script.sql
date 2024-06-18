CREATE DATABASE racaoracao;

USE racaoracao;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

========================
USE racaoracao;

INSERT INTO products (name, description, price, stock) VALUES
('Ração Premium para Cães Adultos', 'Ração de alta qualidade para cães adultos de todas as raças.', 120.00, 50),
('Ração para Gatos Filhotes', 'Ração especialmente formulada para gatos filhotes.', 80.00, 40),
('Ração Hipoalergênica para Cães', 'Ração ideal para cães com alergias alimentares.', 150.00, 30),
('Ração para Gatos Adultos', 'Ração balanceada para gatos adultos.', 90.00, 60),
('Ração Light para Cães', 'Ração com menor teor calórico para cães que precisam perder peso.', 110.00, 20),
('Ração Grain Free para Cães', 'Ração sem grãos para cães com sensibilidade alimentar.', 160.00, 25),
('Ração Orgânica para Gatos', 'Ração orgânica e natural para gatos.', 130.00, 35),
('Ração para Cães Idosos', 'Ração desenvolvida para atender as necessidades nutricionais de cães idosos.', 100.00, 40),
('Ração para Gatos Senior', 'Ração formulada para gatos mais velhos.', 95.00, 50),
('Ração para Cães de Raça Pequena', 'Ração específica para cães de raças pequenas.', 85.00, 45);

========================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
