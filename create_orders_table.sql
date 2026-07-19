-- SQL script to create the `orders` table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    rider_name VARCHAR(255) NOT NULL,
    order_status ENUM('pending', 'completed', 'canceled', 'cancelled') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for testing
INSERT INTO orders (rider_name, order_status) VALUES
('John Doe', 'pending'),
('Jane Smith', 'completed'),
('Alice Johnson', 'canceled');