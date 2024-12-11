-- Create the database
CREATE DATABASE todo_app;

-- Use the database
USE todo_app;

-- Create the tasks table
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample data (optional)
INSERT INTO tasks (task) VALUES 
('Buy groceries'),
('Finish the project report'),
('Call the dentist');
