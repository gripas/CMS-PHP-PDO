<?php
function getplaces($db) {
    $stmt = $db->prepare('SELECT * FROM places');
    $stmt->execute();
    return $stmt->fetchAll();
}
function createplace($db, $name) {
    // Patikrina, ar kategorija su tokiu pavadinimu jau egzistuoja
    $stmt = $db->prepare('SELECT * FROM places WHERE name = :name');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        // Jei kategorija egzistuoja, grąžina klaidą
        return 'place already exists';
    }

    // Jei kategorijos nėra, pridedame ją
    $stmt = $db->prepare('INSERT INTO places (name) VALUES (:name)');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    if ($stmt->execute()) {
        // Jei įterpimo operacija buvo sėkminga, grąžiname teigiamą rezultatą
        return 'place added successfully';
    } else {
        // Jei įterpimo operacija nepavyko, grąžiname klaidą
        return 'Error adding place';
    }
}

function getAllplaces($db) {
    $stmt = $db->prepare("SELECT * FROM places");
    $stmt->execute();

    return $stmt->fetchAll();
}
function updateplace($db, $id, $name) {
    // Patikrina, ar kategorija su tokiu pavadinimu jau egzistuoja (išskyrus atnaujinamąją)
    $stmt = $db->prepare('SELECT * FROM places WHERE name = :name AND id != :id');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        // Jei kategorija egzistuoja, grąžina klaidą
        return 'place already exists';
    }

    // Jei kategorijos nėra, atnaujiname ją
    $stmt = $db->prepare('UPDATE places SET name = :name WHERE id = :id');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        // Jei atnaujinimo operacija buvo sėkminga, grąžina teigiamą rezultatą
        return 'place updated successfully';
    } else {
        // Jei atnaujinimo operacija nepavyko, grąžina klaidą
        return 'Error updating place';
    }
}

function deleteplace($db, $id) {
    $stmt = $db->prepare('DELETE FROM places WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}
