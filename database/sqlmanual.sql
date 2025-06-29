-- ====================================================================
-- SKRIP LENGKAP: RESET DATABASE & ISI DATA DUMMY
-- Versi Final dengan Tabel Alamat & Orders Address Snapshot
-- Cukup jalankan seluruh file ini dari atas ke bawah.
-- ====================================================================

-- BAGIAN 1: HAPUS STRUKTUR LAMA JIKA SUDAH ADA
-- ====================================================================
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders_address; -- Ditambahkan
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS outlets;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS users;

DROP TYPE IF EXISTS user_role CASCADE;
DROP TYPE IF EXISTS outlet_status CASCADE;
DROP TYPE IF EXISTS order_status_enum CASCADE;
DROP TYPE IF EXISTS payment_method_enum CASCADE;
DROP TYPE IF EXISTS payment_status_enum CASCADE;


-- ====================================================================
-- BAGIAN 2: BUAT TIPE DATA ENUM
-- ====================================================================
CREATE TYPE user_role AS ENUM ('admin', 'outlet', 'cust');
CREATE TYPE outlet_status AS ENUM ('pending', 'verified', 'rejected');
-- Status 'dijemput' dan 'dikirim' ditambahkan
CREATE TYPE order_status_enum AS ENUM ('diterima', 'ditolak', 'diambil', 'dicuci','dikirim', 'selesai', 'diulas');
CREATE TYPE payment_method_enum AS ENUM ('transfer', 'cod', 'ewallet');
CREATE TYPE payment_status_enum AS ENUM ('pending', 'lunas', 'gagal', 'cod');


-- ====================================================================
-- BAGIAN 3: BUAT TABEL (STRUKTUR DATABASE)
-- ====================================================================

-- Tabel Users
-- Kolom latitude & longitude dihapus dari sini
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role user_role NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Addresses (BARU)
CREATE TABLE addresses (
    address_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    label VARCHAR(100) NOT NULL, -- Contoh: 'Rumah', 'Kantor'
    recipient_name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address_detail TEXT NOT NULL,
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_user FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Tabel Outlets
CREATE TABLE outlets (
    outlet_id SERIAL PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    latitude DOUBLE PRECISION,
    longitude DOUBLE PRECISION,
    contact_phone VARCHAR(20),
    operating_hours VARCHAR(255),
    status outlet_status DEFAULT 'pending',
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_owner FOREIGN KEY(owner_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Tabel Services
CREATE TABLE services (
    service_id SERIAL PRIMARY KEY,
    outlet_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    CONSTRAINT fk_outlet FOREIGN KEY(outlet_id) REFERENCES outlets(outlet_id) ON DELETE CASCADE
);

-- Tabel Orders
-- Kolom address_id diubah menjadi orders_address_id yang menunjuk ke tabel snapshot
CREATE TABLE orders (
    order_id SERIAL PRIMARY KEY,
    customer_id INT NOT NULL,
    outlet_id INT NOT NULL,
    orders_address_id INT, -- Diubah
    order_date TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2),
    status order_status_enum DEFAULT 'diterima',
    customer_notes TEXT,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_customer FOREIGN KEY(customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_outlet FOREIGN KEY(outlet_id) REFERENCES outlets(outlet_id) ON DELETE CASCADE
    -- FK ke orders_address ditambahkan setelah create orders_address
);

-- Tabel Orders Address (Snapshot)
CREATE TABLE orders_address (
    order_address_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL,
    label VARCHAR(100) NOT NULL,
    recipient_name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address_detail TEXT NOT NULL,
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- Tambah FK di Orders ke Orders Address
ALTER TABLE orders
ADD CONSTRAINT fk_orders_address
FOREIGN KEY (orders_address_id) REFERENCES orders_address(order_address_id) ON DELETE RESTRICT;

-- Tabel Order Items
CREATE TABLE order_items (
    item_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_order FOREIGN KEY(order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    CONSTRAINT fk_service FOREIGN KEY(service_id) REFERENCES services(service_id)
);

-- Tabel Payments
CREATE TABLE payments (
    payment_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method payment_method_enum NOT NULL,
    status payment_status_enum DEFAULT 'pending',
    payment_date TIMESTAMP WITHOUT TIME ZONE,
    CONSTRAINT fk_order FOREIGN KEY(order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- Tabel Reviews
CREATE TABLE reviews (
    review_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL,
    customer_id INT NOT NULL,
    outlet_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    review_date TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order FOREIGN KEY(order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    CONSTRAINT fk_customer FOREIGN KEY(customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_outlet FOREIGN KEY(outlet_id) REFERENCES outlets(outlet_id) ON DELETE CASCADE
);


-- ====================================================================
-- BAGIAN 4: ISI DATA DUMMY
-- ====================================================================

-- Tabel Users
INSERT INTO users (name, email, password, role) VALUES
('Admin Dywash', 'admin@dywash.com', 'password123', 'admin'),
('Budi Laundry', 'budi.laundry@mail.com', 'password123', 'outlet'),
('Siti Laundry', 'siti.laundry@mail.com', 'password123', 'outlet'),
('Andi Pratama', 'andi@mail.com', 'password123', 'cust'),
('Citra Lestari', 'citra@mail.com', 'password123', 'cust'),
('Dewi Anggraini', 'dewi@mail.com', 'password123', 'cust');

-- Tabel Addresses (DATA DUMMY BARU)
-- user_id 4=Andi, 5=Citra, 6=Dewi
INSERT INTO addresses (user_id, label, recipient_name, phone_number, address_detail, latitude, longitude, is_primary) VALUES
(4, 'Rumah', 'Andi Pratama', '081234567890', 'Jl. Darmo Permai III No. 45', -7.2893, 112.7222, true),
(4, 'Kantor', 'Andi (Penerima)', '081234567890', 'Gedung Sinar Mas, Jl. Jend. Sudirman Kav. 10', -7.2600, 112.7400, false),
(5, 'Apartemen', 'Citra Lestari', '081223344557', 'Apartemen Puncak Kertajaya Tower A Lt. 15', -7.2915, 112.7305, true),
(6, 'Rumah Ortu', 'Ibu Anggraini', '081987654322', 'Jl. Rungkut Madya No. 112', -7.2881, 112.7259, true);

-- Tabel Outlets
INSERT INTO outlets (owner_id, name, address, latitude, longitude, contact_phone, operating_hours, status) VALUES
(2, 'KlinKlin Laundry', 'Jl. Mawar No. 10, Surabaya', -7.2905, 112.7248, '081234567890', 'Senin - Sabtu, 08:00 - 20:00', 'verified'),
(3, 'BersihWangi Laundry', 'Jl. Melati No. 25, Surabaya', -7.2879, 112.7291, '081223344556', 'Setiap Hari, 07:00 - 21:00', 'verified'),
(2, 'Cemerlang Laundry', 'Jl. Anggrek No. 5, Surabaya', -7.2922, 112.7211, '081987654321', 'Senin - Jumat, 09:00 - 17:00', 'pending');

-- Tabel Services
INSERT INTO services (outlet_id, name, price, unit) VALUES
(1, 'Cuci Kering Lipat', 7000, 'kg'),
(1, 'Setrika Saja', 5000, 'kg'),
(1, 'Cuci Selimut', 15000, 'pcs'),
(2, 'Cuci Kering Setrika', 8000, 'kg'),
(2, 'Cuci Sepatu', 25000, 'pasang'),
(2, 'Dry Cleaning Jas', 35000, 'pcs');

-- ====================================================================
-- BAGIAN 5: ISI DATA DUMMY (EXTENDED UNTUK TEST SEMUA STATUS)
-- ====================================================================

-- ==========================
-- ORDERS & ORDERS ADDRESS
-- ==========================

-- Order 1 - diulas (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 1, NULL, 'diulas', 'Tolong jangan dicampur dengan pakaian warna putih.');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (1, 'Rumah', 'Andi Pratama', '081234567890', 'Jl. Darmo Permai III No. 45', -7.2893, 112.7222);
UPDATE orders SET orders_address_id = 1 WHERE order_id = 1;

-- Order 2 - selesai (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 2, NULL, 'selesai', 'Test selesai by Andi');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (2, 'Kantor', 'Andi (Penerima)', '081234567890', 'Gedung Sinar Mas, Jl. Jend. Sudirman Kav. 10', -7.2600, 112.7400);
UPDATE orders SET orders_address_id = 2 WHERE order_id = 2;

-- Order 3 - diterima (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 1, NULL, 'diterima', 'Test diterima by Andi');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (3, 'Rumah', 'Andi Pratama', '081234567890', 'Jl. Darmo Permai III No. 45', -7.2893, 112.7222);
UPDATE orders SET orders_address_id = 3 WHERE order_id = 3;

-- Order 4 - diambil (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 2, NULL, 'diambil', 'Test diambil by Andi');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (4, 'Kantor', 'Andi (Penerima)', '081234567890', 'Gedung Sinar Mas, Jl. Jend. Sudirman Kav. 10', -7.2600, 112.7400);
UPDATE orders SET orders_address_id = 4 WHERE order_id = 4;

-- Order 5 - dikirim (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 1, NULL, 'dikirim', 'Test dikirim by Andi');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (5, 'Rumah', 'Andi Pratama', '081234567890', 'Jl. Darmo Permai III No. 45', -7.2893, 112.7222);
UPDATE orders SET orders_address_id = 5 WHERE order_id = 5;

-- Order 6 - ditolak (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 2, NULL, 'ditolak', 'Test ditolak by Andi');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (6, 'Kantor', 'Andi (Penerima)', '081234567890', 'Gedung Sinar Mas, Jl. Jend. Sudirman Kav. 10', -7.2600, 112.7400);
UPDATE orders SET orders_address_id = 6 WHERE order_id = 6;

-- Order 7 - dicuci (Andi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (4, 1, NULL, 'dicuci', 'Test dicuci by Andi');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (7, 'Rumah', 'Andi Pratama', '081234567890', 'Jl. Darmo Permai III No. 45', -7.2893, 112.7222);
UPDATE orders SET orders_address_id = 7 WHERE order_id = 7;


-- ==========================
-- USER LAIN: CITRA & DEWI
-- ==========================

-- Order 8 - diterima (Citra)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (5, 2, NULL, 'diterima', 'Sepatu kanvas, tolong sikat bagian dalamnya juga.');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (8, 'Apartemen', 'Citra Lestari', '081223344557', 'Apartemen Puncak Kertajaya Tower A Lt. 15', -7.2915, 112.7305);
UPDATE orders SET orders_address_id = 8 WHERE order_id = 8;

-- Order 9 - diambil (Dewi)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (6, 1, NULL, 'diambil', 'Ambil di resepsionis tower B.');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (9, 'Rumah Ortu', 'Ibu Anggraini', '081987654322', 'Jl. Rungkut Madya No. 112', -7.2881, 112.7259);
UPDATE orders SET orders_address_id = 9 WHERE order_id = 9;

-- Order 10 - selesai (Citra)
INSERT INTO orders (customer_id, outlet_id, orders_address_id, status, customer_notes)
VALUES (5, 1, NULL, 'selesai', 'Test selesai by Citra');
INSERT INTO orders_address (order_id, label, recipient_name, phone_number, address_detail, latitude, longitude)
VALUES (10, 'Apartemen', 'Citra Lestari', '081223344557', 'Apartemen Puncak Kertajaya Tower B Lt. 10', -7.2915, 112.7306);
UPDATE orders SET orders_address_id = 10 WHERE order_id = 10;


-- ==========================
-- ORDER ITEMS & TOTAL
-- ==========================

INSERT INTO order_items (order_id, service_id, quantity, subtotal) VALUES
(1, 1, 3, 21000),
(2, 1, 2, 14000),
(3, 1, 1, 7000),
(4, 1, 2, 14000),
(5, 1, 2, 14000),
(6, 1, 1, 7000),
(7, 1, 2, 14000),
(8, 5, 1, 25000),
(9, 2, 4, 20000),
(10, 3, 2, 30000);

UPDATE orders SET total_amount = 21000 WHERE order_id = 1;
UPDATE orders SET total_amount = 14000 WHERE order_id = 2;
UPDATE orders SET total_amount = 7000 WHERE order_id = 3;
UPDATE orders SET total_amount = 14000 WHERE order_id = 4;
UPDATE orders SET total_amount = 14000 WHERE order_id = 5;
UPDATE orders SET total_amount = 7000 WHERE order_id = 6;
UPDATE orders SET total_amount = 14000 WHERE order_id = 7;
UPDATE orders SET total_amount = 25000 WHERE order_id = 8;
UPDATE orders SET total_amount = 20000 WHERE order_id = 9;
UPDATE orders SET total_amount = 30000 WHERE order_id = 10;


-- ==========================
-- PAYMENTS
-- ==========================

INSERT INTO payments (order_id, amount, payment_method, status, payment_date) VALUES
(1, 21000, 'ewallet', 'lunas', CURRENT_TIMESTAMP),
(2, 14000, 'transfer', 'lunas', CURRENT_TIMESTAMP),
(3, 7000, 'transfer', 'pending', NULL),
(4, 14000, 'transfer', 'lunas', CURRENT_TIMESTAMP),
(5, 14000, 'cod', 'lunas', CURRENT_TIMESTAMP),
(6, 7000, 'ewallet', 'gagal', CURRENT_TIMESTAMP),
(7, 14000, 'transfer', 'lunas', CURRENT_TIMESTAMP),
(8, 25000, 'cod', 'lunas', CURRENT_TIMESTAMP),
(9, 20000, 'transfer', 'pending', NULL),
(10, 30000, 'ewallet', 'lunas', CURRENT_TIMESTAMP);


-- ==========================
-- REVIEWS
-- ==========================

INSERT INTO reviews (order_id, customer_id, outlet_id, rating, comment) VALUES
(1, 4, 1, 5, 'Hasilnya bersih dan wangi. Pengirimannya juga tepat waktu. Sangat direkomendasikan!');
