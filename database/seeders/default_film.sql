-- SQL untuk insert film default: Jujutsu Kaisen Movie
-- Total: 134 kursi (termasuk 3 couple set)

-- Insert Film
INSERT INTO `films` (`title`, `price`, `poster`, `is_active`, `created_at`, `updated_at`)
VALUES (
    'Jujutsu Kaisen Movie Shibuya Incident x Culling Game',
    45000,
    'images/poster1.jpg',
    1,
    NOW(),
    NOW()
);

-- Set variable untuk film_id (gunakan ID terakhir yang di-insert)
SET @film_id = LAST_INSERT_ID();

-- Baris J (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'J1', 'available', NOW(), NOW()),
(@film_id, 'J2', 'available', NOW(), NOW()),
(@film_id, 'J3', 'available', NOW(), NOW()),
(@film_id, 'J4', 'available', NOW(), NOW()),
(@film_id, 'J5', 'available', NOW(), NOW()),
(@film_id, 'J6', 'available', NOW(), NOW()),
(@film_id, 'J7', 'available', NOW(), NOW()),
(@film_id, 'J8', 'available', NOW(), NOW()),
(@film_id, 'J9', 'available', NOW(), NOW()),
(@film_id, 'J10', 'available', NOW(), NOW()),
(@film_id, 'J11', 'available', NOW(), NOW()),
(@film_id, 'J12', 'available', NOW(), NOW()),
(@film_id, 'J13', 'available', NOW(), NOW()),
(@film_id, 'J14', 'available', NOW(), NOW());

-- Baris H (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'H1', 'available', NOW(), NOW()),
(@film_id, 'H2', 'available', NOW(), NOW()),
(@film_id, 'H3', 'available', NOW(), NOW()),
(@film_id, 'H4', 'available', NOW(), NOW()),
(@film_id, 'H5', 'available', NOW(), NOW()),
(@film_id, 'H6', 'available', NOW(), NOW()),
(@film_id, 'H7', 'available', NOW(), NOW()),
(@film_id, 'H8', 'available', NOW(), NOW()),
(@film_id, 'H9', 'available', NOW(), NOW()),
(@film_id, 'H10', 'available', NOW(), NOW()),
(@film_id, 'H11', 'available', NOW(), NOW()),
(@film_id, 'H12', 'available', NOW(), NOW()),
(@film_id, 'H13', 'available', NOW(), NOW()),
(@film_id, 'H14', 'available', NOW(), NOW());

-- Baris G (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'G1', 'available', NOW(), NOW()),
(@film_id, 'G2', 'available', NOW(), NOW()),
(@film_id, 'G3', 'available', NOW(), NOW()),
(@film_id, 'G4', 'available', NOW(), NOW()),
(@film_id, 'G5', 'available', NOW(), NOW()),
(@film_id, 'G6', 'available', NOW(), NOW()),
(@film_id, 'G7', 'available', NOW(), NOW()),
(@film_id, 'G8', 'available', NOW(), NOW()),
(@film_id, 'G9', 'available', NOW(), NOW()),
(@film_id, 'G10', 'available', NOW(), NOW()),
(@film_id, 'G11', 'available', NOW(), NOW()),
(@film_id, 'G12', 'available', NOW(), NOW()),
(@film_id, 'G13', 'available', NOW(), NOW()),
(@film_id, 'G14', 'available', NOW(), NOW());

-- Baris F (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'F1', 'available', NOW(), NOW()),
(@film_id, 'F2', 'available', NOW(), NOW()),
(@film_id, 'F3', 'available', NOW(), NOW()),
(@film_id, 'F4', 'available', NOW(), NOW()),
(@film_id, 'F5', 'available', NOW(), NOW()),
(@film_id, 'F6', 'available', NOW(), NOW()),
(@film_id, 'F7', 'available', NOW(), NOW()),
(@film_id, 'F8', 'available', NOW(), NOW()),
(@film_id, 'F9', 'available', NOW(), NOW()),
(@film_id, 'F10', 'available', NOW(), NOW()),
(@film_id, 'F11', 'available', NOW(), NOW()),
(@film_id, 'F12', 'available', NOW(), NOW()),
(@film_id, 'F13', 'available', NOW(), NOW()),
(@film_id, 'F14', 'available', NOW(), NOW());

-- Baris E (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'E1', 'available', NOW(), NOW()),
(@film_id, 'E2', 'available', NOW(), NOW()),
(@film_id, 'E3', 'available', NOW(), NOW()),
(@film_id, 'E4', 'available', NOW(), NOW()),
(@film_id, 'E5', 'available', NOW(), NOW()),
(@film_id, 'E6', 'available', NOW(), NOW()),
(@film_id, 'E7', 'available', NOW(), NOW()),
(@film_id, 'E8', 'available', NOW(), NOW()),
(@film_id, 'E9', 'available', NOW(), NOW()),
(@film_id, 'E10', 'available', NOW(), NOW()),
(@film_id, 'E11', 'available', NOW(), NOW()),
(@film_id, 'E12', 'available', NOW(), NOW()),
(@film_id, 'E13', 'available', NOW(), NOW()),
(@film_id, 'E14', 'available', NOW(), NOW());

-- Baris D (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'D1', 'available', NOW(), NOW()),
(@film_id, 'D2', 'available', NOW(), NOW()),
(@film_id, 'D3', 'available', NOW(), NOW()),
(@film_id, 'D4', 'available', NOW(), NOW()),
(@film_id, 'D5', 'available', NOW(), NOW()),
(@film_id, 'D6', 'available', NOW(), NOW()),
(@film_id, 'D7', 'available', NOW(), NOW()),
(@film_id, 'D8', 'available', NOW(), NOW()),
(@film_id, 'D9', 'available', NOW(), NOW()),
(@film_id, 'D10', 'available', NOW(), NOW()),
(@film_id, 'D11', 'available', NOW(), NOW()),
(@film_id, 'D12', 'available', NOW(), NOW()),
(@film_id, 'D13', 'available', NOW(), NOW()),
(@film_id, 'D14', 'available', NOW(), NOW());

-- Baris C (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'C1', 'available', NOW(), NOW()),
(@film_id, 'C2', 'available', NOW(), NOW()),
(@film_id, 'C3', 'available', NOW(), NOW()),
(@film_id, 'C4', 'available', NOW(), NOW()),
(@film_id, 'C5', 'available', NOW(), NOW()),
(@film_id, 'C6', 'available', NOW(), NOW()),
(@film_id, 'C7', 'available', NOW(), NOW()),
(@film_id, 'C8', 'available', NOW(), NOW()),
(@film_id, 'C9', 'available', NOW(), NOW()),
(@film_id, 'C10', 'available', NOW(), NOW()),
(@film_id, 'C11', 'available', NOW(), NOW()),
(@film_id, 'C12', 'available', NOW(), NOW()),
(@film_id, 'C13', 'available', NOW(), NOW()),
(@film_id, 'C14', 'available', NOW(), NOW());

-- Baris B (14 kursi)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'B1', 'available', NOW(), NOW()),
(@film_id, 'B2', 'available', NOW(), NOW()),
(@film_id, 'B3', 'available', NOW(), NOW()),
(@film_id, 'B4', 'available', NOW(), NOW()),
(@film_id, 'B5', 'available', NOW(), NOW()),
(@film_id, 'B6', 'available', NOW(), NOW()),
(@film_id, 'B7', 'available', NOW(), NOW()),
(@film_id, 'B8', 'available', NOW(), NOW()),
(@film_id, 'B9', 'available', NOW(), NOW()),
(@film_id, 'B10', 'available', NOW(), NOW()),
(@film_id, 'B11', 'available', NOW(), NOW()),
(@film_id, 'B12', 'available', NOW(), NOW()),
(@film_id, 'B13', 'available', NOW(), NOW()),
(@film_id, 'B14', 'available', NOW(), NOW());

-- Baris A (17 unit: 14 kursi reguler + 3 couple set)
INSERT INTO `tickets` (`film_id`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(@film_id, 'A1', 'available', NOW(), NOW()),
(@film_id, 'Couple Set A3 (A2-A3)', 'available', NOW(), NOW()),
(@film_id, 'Couple Set A2 (A4-A5)', 'available', NOW(), NOW()),
(@film_id, 'A6', 'available', NOW(), NOW()),
(@film_id, 'A7', 'available', NOW(), NOW()),
(@film_id, 'A8', 'available', NOW(), NOW()),
(@film_id, 'A9', 'available', NOW(), NOW()),
(@film_id, 'A10', 'available', NOW(), NOW()),
(@film_id, 'A11', 'available', NOW(), NOW()),
(@film_id, 'A12', 'available', NOW(), NOW()),
(@film_id, 'A13', 'available', NOW(), NOW()),
(@film_id, 'A14', 'available', NOW(), NOW()),
(@film_id, 'A15', 'available', NOW(), NOW()),
(@film_id, 'A16', 'available', NOW(), NOW()),
(@film_id, 'A17', 'available', NOW(), NOW()),
(@film_id, 'A18', 'available', NOW(), NOW()),
(@film_id, 'Couple Set A1 (A19-A20)', 'available', NOW(), NOW());

-- Total kursi yang diinsert: 134
-- Breakdown:
-- Baris J-B: 8 baris × 14 kursi = 112 kursi
-- Baris A: 14 kursi reguler + 3 couple set = 17 unit
-- Total: 112 + 17 = 129 unit (mencakup 134 kursi fisik)
