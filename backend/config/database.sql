-- Real Estate Database Schema + Seed Data
-- Import this file in phpMyAdmin or via:
--   mysql -u root -p < backend/config/database.sql
-- Then run:
--   php backend/seed.php
-- to create the admin user with a properly hashed password.

CREATE DATABASE IF NOT EXISTS real_estate;
USE real_estate;

-- ── Drop existing tables (reverse FK order) ──────────────────────────────────
DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS users;

-- ── Tables ────────────────────────────────────────────────────────────────────

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL,
    type ENUM('sale', 'rent') NOT NULL,
    category ENUM('house', 'apartment', 'villa', 'land', 'commercial') NOT NULL,
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    area DECIMAL(10, 2),
    address VARCHAR(255),
    city VARCHAR(100),
    country VARCHAR(100),
    image VARCHAR(255),
    agent_id INT,
    status ENUM('available', 'sold', 'rented') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    message TEXT NOT NULL,
    property_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL
);

-- ── Seed: Properties (20 listings) ──────────────────────────────────────────

INSERT INTO properties (title, description, price, type, category, bedrooms, bathrooms, area, address, city, country, image, status) VALUES

-- 1
(
    'Modern Villa with Pool',
    'A stunning modern villa featuring an open-plan living area, floor-to-ceiling windows, a private pool, and a beautifully landscaped garden. Perfect for families seeking luxury and comfort.',
    450000, 'sale', 'villa', 5, 4, 320,
    '14 Sunset Boulevard', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800',
    'available'
),

-- 2
(
    'City Center Apartment',
    'Bright and spacious apartment in the heart of the city. Close to restaurants, shops, and public transport. Features a modern kitchen and a large balcony with city views.',
    850, 'rent', 'apartment', 2, 1, 85,
    '7 Nena Tereze Street', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
    'available'
),

-- 3
(
    'Cozy Family House',
    'A warm and welcoming family home with a large backyard, two-car garage, and renovated kitchen. Located in a quiet residential area with excellent schools nearby.',
    185000, 'sale', 'house', 4, 3, 210,
    '22 Liridon Street', 'Prizren', 'Kosovo',
    'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800',
    'available'
),

-- 4
(
    'Downtown Studio Apartment',
    'Compact and stylish studio apartment ideal for students or young professionals. Fully furnished, utilities included. Walking distance to the university.',
    450, 'rent', 'apartment', 1, 1, 42,
    '3 Fehmi Agani Street', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
    'available'
),

-- 5
(
    'Commercial Space – Ground Floor',
    'Prime ground-floor commercial space suitable for a cafe, office, or retail store. High foot traffic area, open layout, separate storage room.',
    1200, 'rent', 'commercial', 0, 1, 130,
    '55 Bill Clinton Boulevard', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800',
    'available'
),

-- 6
(
    'Land Plot – Residential Zone',
    'Flat land plot in a residential zone with all utilities available at the boundary. Building permit already obtained. Great opportunity to build your dream home.',
    75000, 'sale', 'land', 0, 0, 600,
    'Zone B, Block 12', 'Gjilan', 'Kosovo',
    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800',
    'available'
),

-- 7
(
    'Luxury Penthouse',
    'Exclusive penthouse on the 12th floor with panoramic city views, private terrace, high-end finishes, and a smart home system. Two underground parking spots included.',
    620000, 'sale', 'apartment', 3, 3, 195,
    '1 Grand Plaza Tower', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800',
    'available'
),

-- 8
(
    'Traditional Stone House',
    'Charming traditional stone house in the historic old town. Fully restored while preserving original architectural details. Unique character and great investment potential.',
    230000, 'sale', 'house', 3, 2, 160,
    '8 Kalaja Quarter', 'Prizren', 'Kosovo',
    'https://images.unsplash.com/photo-1518780664697-55e3ad937233?w=800',
    'available'
),

-- 9
(
    'Modern 2-Bedroom Apartment',
    'Newly built apartment in a gated residence with 24/7 security, underground parking, and a shared gym. Bright open plan living area with high-quality finishes throughout.',
    95000, 'sale', 'apartment', 2, 1, 78,
    '12 Ismail Qemali Street', 'Peja', 'Kosovo',
    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
    'available'
),

-- 10
(
    'Mountain View Villa',
    'Spectacular villa on the hillside with breathtaking mountain views. Features a large terrace, open fireplace, modern kitchen, and a private driveway. Ideal as a family residence or holiday home.',
    380000, 'sale', 'villa', 4, 3, 280,
    '3 Bjeshket e Nemuna Road', 'Peja', 'Kosovo',
    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800',
    'available'
),

-- 11
(
    'Executive Office Suite',
    'Fully fitted executive office suite on the 5th floor of a modern business tower. Includes 4 private offices, meeting room, reception area, and fiber internet. Ideal for law firms or agencies.',
    2500, 'rent', 'commercial', 0, 2, 220,
    '18 UCK Street', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1497215842964-222b430dc094?w=800',
    'available'
),

-- 12
(
    'Two-Bedroom Apartment in Old Town',
    'Charming two-bedroom apartment just steps from the old bazaar. Recently renovated with new floors, kitchen, and bathroom. Quiet street, great natural light.',
    550, 'rent', 'apartment', 2, 1, 70,
    '5 Remzi Ademaj Street', 'Prizren', 'Kosovo',
    'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800',
    'available'
),

-- 13
(
    'Prime Building Land – City Edge',
    'Large flat plot in a rapidly developing area on the city edge. Zoned for residential multi-floor construction. Utilities at the boundary, road access fully paved.',
    150000, 'sale', 'land', 0, 0, 1200,
    'Zone A, Veternik', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=800',
    'available'
),

-- 14
(
    'Newly Built Detached House',
    'Brand new three-bedroom house in a quiet residential street. Features underfloor heating, solar panels, double garage, and a generous garden. Energy class A.',
    140000, 'sale', 'house', 3, 2, 175,
    '9 Liria Street', 'Ferizaj', 'Kosovo',
    'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?w=800',
    'available'
),

-- 15
(
    'Spacious Duplex Apartment',
    'Impressive duplex spread over two floors with a private rooftop terrace. Open-plan kitchen and living room downstairs, three bedrooms and two bathrooms upstairs. Parking included.',
    195000, 'sale', 'apartment', 3, 2, 145,
    '27 Dardania Boulevard', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?w=800',
    'available'
),

-- 16
(
    'Countryside Farmhouse',
    'Peaceful rural farmhouse set on a large plot with fruit trees and vegetable garden. Solid construction, fully habitable, with a barn and well water. Perfect for those seeking rural tranquility.',
    110000, 'sale', 'house', 4, 2, 190,
    'Village of Banja', 'Peja', 'Kosovo',
    'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?w=800',
    'available'
),

-- 17
(
    'Boutique Hotel Building',
    'Fully operating boutique hotel in the heart of the old bazaar district. 12 rooms, reception, bar area, and rooftop terrace. Excellent revenue history. Turnkey sale including furniture and equipment.',
    850000, 'sale', 'commercial', 0, 12, 650,
    '2 Shadervan Square', 'Prizren', 'Kosovo',
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800',
    'available'
),

-- 18
(
    'Affordable Studio for Rent',
    'Neat and clean studio apartment on the second floor. Tiled floors, modern bathroom, and a kitchenette. Bills not included. Ideal for a single person or student.',
    300, 'rent', 'apartment', 1, 1, 36,
    '14 Skenderbeu Street', 'Gjilan', 'Kosovo',
    'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800',
    'available'
),

-- 19
(
    'Residential Land in Ferizaj',
    'Flat and clean plot in a growing residential zone. Water and electricity available at the boundary. Located near a new school and shopping centre. Ready to build.',
    45000, 'sale', 'land', 0, 0, 500,
    'Zone C, Kerpaci', 'Ferizaj', 'Kosovo',
    'https://images.unsplash.com/photo-1524813686514-a57563d77965?w=800',
    'available'
),

-- 20
(
    'High-End Furnished Apartment',
    'Fully furnished luxury apartment with top-of-the-line appliances, marble bathrooms, and smart home controls. Available for long-term rent. Suitable for executives or diplomats.',
    1500, 'rent', 'apartment', 2, 2, 110,
    '4 Agim Ramadani Street', 'Prishtina', 'Kosovo',
    'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800',
    'available'
);

-- ── Seed: Admin user ──────────────────────────────────────────────────────────
-- Admin user is inserted by seed.php (requires PHP's password_hash).
-- Run: php backend/seed.php
