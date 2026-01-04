
-- ====================================
-- Table: utilisateurs
-- ====================================
DROP TABLE IF EXISTS commandes_placees;
DROP TABLE IF EXISTS commandes_disponibles;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS utilisateurs;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (date_envoi),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE commandes_disponibles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    quantite_disponible INT NOT NULL DEFAULT 0,
    date_insertion DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nom (nom),
    INDEX idx_prix (prix)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE commandes_placees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produit_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    quantite_commande INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    prix_total DECIMAL(10, 2) NOT NULL,
    user_id INT,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'validee', 'livree', 'annulee') DEFAULT 'en_attente',
    FOREIGN KEY (produit_id) REFERENCES commandes_disponibles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    INDEX idx_produit (produit_id),
    INDEX idx_date (date_commande),
    INDEX idx_statut (statut),
    INDEX idx_user_date (user_id, date_commande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Insertion des produits disponibles
INSERT INTO commandes_disponibles (id, nom, prix, image, quantite_disponible, date_insertion) VALUES
(1, 'Fanta Orange', 150, 'produit/fanta_PNG46.png', 800, NOW()),
(2, 'Fanta Raisin', 150, 'produit/fanta_PNG47.png', 700, NOW()),
(3, 'Fanta Citron', 150, 'produit/fanta_PNG48.png', 750, NOW()),
(4, 'Fanta Pomme', 150, 'produit/fanta_PNG49.png', 600, NOW()),
(5, 'Fanta Raisin', 200, 'produit/Canettes-Fanta-Raisin.png', 650, NOW()),
(6, 'Fanta Citron', 200, 'produit/fanta_PNG25.png', 630, NOW()),
(7, 'Fanta Ananas', 210, 'produit/fanta_PNG26.png', 690, NOW()),
(8, 'Fanta Orange', 220, 'produit/fanta_PNG27.png', 550, NOW()),
(9, 'Fanta Kiwi et Raisin', 200, 'produit/fantaexcoticcan_150x@2x.avif', 500, NOW()),
(10, 'Fanta Pomme', 250, 'produit/fanta_PNG28.png', 490, NOW()),
(11, 'Fanta Pomme', 240, 'produit/fanta_PNG31.png', 450, NOW()),
(12, 'Fanta Pomme', 250, 'produit/fanta_PNG29.png', 350, NOW()),
(13, 'Fanta Pomme', 250, 'produit/fanta_PNG30.png', 375, NOW());

-- Insertion d'un utilisateur admin par défaut
-- Mot de passe: admin123 (à changer après la première connexion)
INSERT INTO utilisateurs (username, email, password, role, created_at) VALUES
('admin', 'admin@fanta.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());


-- Vue: Statistiques des produits
CREATE OR REPLACE VIEW v_stats_produits AS
SELECT 
    cd.id,
    cd.nom,
    cd.prix,
    cd.quantite_disponible,
    COALESCE(SUM(cp.quantite_commande), 0) AS total_commandes,
    COALESCE(SUM(cp.prix_total), 0) AS revenus_total
FROM commandes_disponibles cd
LEFT JOIN commandes_placees cp ON cd.id = cp.produit_id
GROUP BY cd.id, cd.nom, cd.prix, cd.quantite_disponible;

-- Vue: Commandes récentes
CREATE OR REPLACE VIEW v_commandes_recentes AS
SELECT 
    cp.id,
    cp.nom AS produit,
    cp.quantite_commande,
    cp.prix_total,
    u.username,
    u.email,
    cp.date_commande,
    cp.statut
FROM commandes_placees cp
LEFT JOIN utilisateurs u ON cp.user_id = u.id
ORDER BY cp.date_commande DESC;



DELIMITER //

-- Procédure: Placer une commande
CREATE PROCEDURE sp_placer_commande(
    IN p_produit_id INT,
    IN p_quantite INT,
    IN p_user_id INT
)
BEGIN
    DECLARE v_nom VARCHAR(100);
    DECLARE v_prix DECIMAL(10, 2);
    DECLARE v_quantite_dispo INT;
    DECLARE v_prix_total DECIMAL(10, 2);
    
    -- Récupérer les informations du produit
    SELECT nom, prix, quantite_disponible 
    INTO v_nom, v_prix, v_quantite_dispo
    FROM commandes_disponibles 
    WHERE id = p_produit_id;
    
    -- Vérifier la disponibilité
    IF v_quantite_dispo >= p_quantite THEN
        -- Calculer le prix total
        SET v_prix_total = v_prix * p_quantite;
        
        -- Insérer la commande
        INSERT INTO commandes_placees (produit_id, nom, quantite_commande, prix_unitaire, prix_total, user_id)
        VALUES (p_produit_id, v_nom, p_quantite, v_prix, v_prix_total, p_user_id);
        
        -- Mettre à jour la quantité disponible
        UPDATE commandes_disponibles 
        SET quantite_disponible = quantite_disponible - p_quantite 
        WHERE id = p_produit_id;
        
        SELECT 'Commande placée avec succès' AS message;
    ELSE
        SELECT 'Quantité insuffisante' AS message;
    END IF;
END //

-- Procédure: Obtenir le top des produits
CREATE PROCEDURE sp_top_produits(IN limite INT)
BEGIN
    SELECT 
        nom,
        SUM(quantite_commande) AS total_vendu,
        SUM(prix_total) AS revenus
    FROM commandes_placees
    GROUP BY nom
    ORDER BY total_vendu DESC
    LIMIT limite;
END //

DELIMITER ;



DELIMITER //

-- Trigger: Vérifier la quantité avant insertion
CREATE TRIGGER tr_verifier_quantite
BEFORE INSERT ON commandes_placees
FOR EACH ROW
BEGIN
    DECLARE v_quantite_dispo INT;
    
    SELECT quantite_disponible INTO v_quantite_dispo
    FROM commandes_disponibles
    WHERE id = NEW.produit_id;
    
    IF v_quantite_dispo < NEW.quantite_commande THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantité insuffisante en stock';
    END IF;
END //

DELIMITER ;



SELECT 'Base de données FANTA créée avec succès!' AS message;