-- ====================================================================
-- SKRIP LENGKAP: RESET DATABASE & ISI DATA DUMMY
-- Cukup jalankan seluruh file ini dari atas ke bawah.
-- ====================================================================

-- BAGIAN 1: HAPUS STRUKTUR LAMA JIKA SUDAH ADA
-- ====================================================================
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS outlets;
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
CREATE TYPE order_status_enum AS ENUM ('diterima', 'ditolak', 'diproses', 'selesai', 'diulas');
CREATE TYPE payment_method_enum AS ENUM ('transfer', 'cod', 'ewallet');
CREATE TYPE payment_status_enum AS ENUM ('pending', 'lunas', 'gagal');


-- ====================================================================
-- BAGIAN 3: BUAT TABEL (STRUKTUR DATABASE)
-- ====================================================================

-- Tabel Users
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role user_role NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Outlets
CREATE TABLE outlets (
    outlet_id SERIAL PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
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
CREATE TABLE orders (
    order_id SERIAL PRIMARY KEY,
    customer_id INT NOT NULL,
    outlet_id INT NOT NULL,
    order_date TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2),
    status order_status_enum DEFAULT 'diterima',
    customer_notes TEXT,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_customer FOREIGN KEY(customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_outlet FOREIGN KEY(outlet_id) REFERENCES outlets(outlet_id) ON DELETE CASCADE
);

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


-- Tabel Outlets
INSERT INTO outlets (owner_id, name, address, contact_phone, operating_hours, status) VALUES
(2, 'KlinKlin Laundry', 'Jl. Mawar No. 10, Surabaya', '081234567890', 'Senin - Sabtu, 08:00 - 20:00', 'verified'),
(3, 'BersihWangi Laundry', 'Jl. Melati No. 25, Surabaya', '081223344556', 'Setiap Hari, 07:00 - 21:00', 'verified'),
(2, 'Cemerlang Laundry', 'Jl. Anggrek No. 5, Surabaya', '081987654321', 'Senin - Jumat, 09:00 - 17:00', 'pending');


-- Tabel Services
INSERT INTO services (outlet_id, name, price, unit) VALUES
(1, 'Cuci Kering Lipat', 7000, 'kg'),
(1, 'Setrika Saja', 5000, 'kg'),
(1, 'Cuci Selimut', 15000, 'pcs'),
(2, 'Cuci Kering Setrika', 8000, 'kg'),
(2, 'Cuci Sepatu', 25000, 'pasang'),
(2, 'Dry Cleaning Jas', 35000, 'pcs');


-- Tabel Orders
INSERT INTO orders (customer_id, outlet_id, status, customer_notes) VALUES
(4, 1, 'diulas', 'Tolong jangan dicampur dengan pakaian warna putih.'),
(5, 2, 'selesai', 'Sepatu kanvas, tolong sikat bagian dalamnya juga.'),
(4, 2, 'diproses', NULL);


-- Tabel Order Items & Update Total
INSERT INTO order_items (order_id, service_id, quantity, subtotal) VALUES
(1, 1, 3, 21000),
(2, 5, 1, 25000),
(3, 4, 2.5, 20000);

UPDATE orders SET total_amount = 21000 WHERE order_id = 1;
UPDATE orders SET total_amount = 25000 WHERE order_id = 2;
UPDATE orders SET total_amount = 20000 WHERE order_id = 3;


-- Tabel Payments
INSERT INTO payments (order_id, amount, payment_method, status, payment_date) VALUES
(1, 21000, 'ewallet', 'lunas', '2025-06-13 10:30:00'),
(2, 25000, 'cod', 'lunas', '2025-06-14 15:00:00'),
(3, 20000, 'transfer', 'pending', NULL);


-- Tabel Reviews
INSERT INTO reviews (order_id, customer_id, outlet_id, rating, comment) VALUES
(1, 4, 1, 5, 'Hasilnya bersih dan wangi. Pengirimannya juga tepat waktu. Sangat direkomendasikan!');