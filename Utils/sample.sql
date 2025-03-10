INSERT INTO walls (name, location, image_url) VALUES
('Main Wall', 'Gym A', 'http://example.com/walls/main.jpg'),
('Bouldering Wall', 'Gym B', 'http://example.com/walls/boulder.jpg'),
('Training Wall', 'Gym C', 'http://example.com/walls/training.jpg');

INSERT INTO sectors (name, wall_id, image_url) VALUES
('Left Sector', 1, 'http://example.com/sectors/left.jpg'),
('Right Sector', 1, 'http://example.com/sectors/right.jpg'),
('Bouldering Sector 1', 2, 'http://example.com/sectors/boulder1.jpg'),
('Training Sector', 3, 'http://example.com/sectors/training.jpg');

INSERT INTO routes (name, sector_id, difficulty, color, image_url, route_setter_id) VALUES
('Easy Route', 1, '5a', 'Yellow', 'http://example.com/routes/easy.jpg', 1),
('Intermediate Route', 1, '6b', 'Green', 'http://example.com/routes/intermediate.jpg', 1),
('Hard Route', 2, '7a', 'Red', 'http://example.com/routes/hard.jpg', 2),
('Boulder Challenge', 3, 'V3', 'Blue', 'http://example.com/routes/boulder.jpg', 2),
('Training Route', 4, '6c', 'Black', 'http://example.com/routes/training.jpg', 3);
