<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['orderId'];
$signature = $data['signature'];
$filename = isset($data['filename']) ? $data['filename'] : 'signature_' . $orderId . '_' . time() . '.jpg';

// Créer le dossier signatures s'il n'existe pas
if (!is_dir('signatures')) {
    mkdir('signatures', 0755, true);
}

// Extraire les données base64
$signature = str_replace('data:image/png;base64,', '', $signature);
$signature = str_replace(' ', '+', $signature);
$imageData = base64_decode($signature);

// Chemin complet du fichier
$filepath = 'signatures/' . $filename;

// Sauvegarder l'image
if (file_put_contents($filepath, $imageData)) {
    echo json_encode(['success' => true, 'filename' => $filename, 'path' => $filepath]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur sauvegarde fichier']);
}
?>