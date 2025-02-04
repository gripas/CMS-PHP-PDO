<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
define('IS_ADMIN', true);

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');

require_once ROOT_PATH . 'security/config.php';
require_once ROOT_PATH . 'core/functions/functions.php';


 $db = getDBConnection($config);
secureSession($db, $prefix);
$language_code = getLanguageSetting($db, $prefix);
$translations = getTranslations($db, $prefix, $language_code);

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{  if (!validateCSRFToken($_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }
    $id = intval($_POST['id']); 
   
    try {
        $img_url = null;
        $img_name = null;
        
      //  $readmore = null;
        $addonFolder ="jd_simple_zer_img"; // Addon default directory

        // Check if directory exists and if not, create it
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/uploads/".$addonFolder)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/uploads/".$addonFolder, 0755);
        }

        if (isset($_FILES['file_id']) && $_FILES['file_id']['error'] == 0) {
            $uploaded_file = $_FILES["file_id"];
            $result = uploadFile($uploaded_file, $db, $prefix, $addonFolder);
            $img_url = $result['img_url'];
            $img_name = $result['img_name'];
            $readmore = $result['readmore'];
            $_SESSION['success_message'] = t("The file has been successfully uploaded to the main directory as well as the addon directory");
        
        } elseif (isset($_POST['brand_icone_id']) && !empty($_POST['brand_icone_id'])) {
            $file = getFileById($db, $prefix, $_POST['brand_icone_id']);
            
            // Copy the existing file to the new directory
            $old_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $file['name'];
            $new_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/".$addonFolder."/" . $file['name'];
            
            if (copy($old_path, $new_path)) {
                $img_url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/uploads/".$addonFolder."/" . $file['name'];
                $img_name = $file['name'];
                $_SESSION['success_message'] = t("File inserting to addon directory successfully.");
            } else {
                throw new Exception(t("Error copying existing file.". $error['message']));
            }
        } else {
           
            throw new Exception(t("No file was uploaded or selected."));
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $lang_en_title = $_POST['lang_en_title'];
        $lang_en_description = $_POST['lang_en_description'];
        $readmore = $_POST['readmore'];
        $place_id = $_POST['addon_place_id'];
        $menu_id = $_POST['addon_menu_id'];
        $addon_id = $id;
        $stmt = $db->prepare("INSERT INTO " . $prefix['table_prefix'] . "_jd_simple_zer (title, description, lang_en_title, lang_en_description, img_url, img_name, readmore, menu_id, place_id, addon_id) VALUES (:title, :description, :lang_en_title, :lang_en_description, :img_url, :img_name, :readmore, :menu_id, :place_id, :addon_id)");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':lang_en_title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':lang_en_description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':img_url', $img_url, PDO::PARAM_STR);
        $stmt->bindParam(':img_name', $img_name, PDO::PARAM_STR); 
        $stmt->bindParam(':readmore', $readmore, PDO::PARAM_STR); //readmore
        $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
        $stmt->bindParam(':place_id', $place_id, PDO::PARAM_INT);
        $stmt->bindParam(':addon_id', $addon_id, PDO::PARAM_INT);
    
        $stmt->execute();
        $_SESSION['success_message'] = t("Adding the plugin was successful.");
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
    
    header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/core/tools/addons_model.php?name=jd_simple_zer&id=' . $id);

    exit();
}
