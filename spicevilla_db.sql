-- Create database
CREATE DATABASE IF NOT EXISTS spicevilla_db;
USE spicevilla_db;

-- Table for Users (Customers and Admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords
    email VARCHAR(100) UNIQUE,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Menu Items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50), -- e.g., Pizza, Indian, Chinese, Pasta & Rolls, Burgers & Sandwiches, Shakes & Mocktails
    image_url VARCHAR(255), -- Optional: URL to an image of the item
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_address TEXT,
    contact_number VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for Order Items (details of each item in an order)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_order DECIMAL(10, 2) NOT NULL, -- Price of the item when the order was placed
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- Insert a default admin user (password: admin123) - CHANGE THIS IN PRODUCTION!
INSERT INTO users (username, password, email, role) VALUES
('admin', '$2y$10$Qj.WJ.N.W.Z.T.R.U.V.Y.A.X.D.C.E.F.G.H.I.J.K.L.M.N.O.P.Q.R.S.T.U.V.W.X.Y.Z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z', 'admin@spicevilla.com', 'admin');
-- The password 'admin123' is hashed using password_hash('admin123', PASSWORD_DEFAULT) in PHP.
-- You should generate a new hash using PHP for a real application.
-- For demonstration, a placeholder hash is used. In a real scenario, you'd run a PHP script to generate this.
-- Example of generating hash in PHP: echo password_hash('admin123', PASSWORD_DEFAULT);
