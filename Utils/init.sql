CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hashed VARCHAR(255) NOT NULL,
  role ENUM('user', 'route_setter', 'admin') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   
  last_edit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE walls (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  location TEXT,
  image_url TEXT, -- Path to an image of the wall
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_edit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE sectors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  wall_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  image_url TEXT, -- Path to an image of the sector
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_edit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE routes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sector_id INT NOT NULL,
  route_setter_id INT NOT NULL, -- User who set the route
  name VARCHAR(255) NOT NULL,
  status ENUM('active', 'inactive') DEFAULT 'active', -- Route status
  difficulty VARCHAR(10), -- Difficulty rating (4c-7a for example)
  color VARCHAR(20), -- For bouldering (yellow, purple, etc.)
  image_url TEXT, -- Image of the route
  description TEXT, -- Route description
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_edit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE route_status (
  user_id INT NOT NULL,
  route_id INT NOT NULL,
  status ENUM('project', 'completed', 'favorite') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_edit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, route_id)
);

CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  route_id INT NOT NULL,
  evaluation INT, -- A numerical rating
  comment TEXT, -- User comment
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
