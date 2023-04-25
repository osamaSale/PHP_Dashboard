CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
email VARCHAR(30) NOT NULL unique,
password VARCHAR(255),
image varchar(255),
fileImage varchar(255),
phone varchar(255),
authorization varchar(255),
cloudinary_id VARCHAR(255) 
)
