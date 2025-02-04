<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
define('IS_ADMIN', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/security/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/functions/functions.php';

$db = getDBConnection($config);
secureSession($db, $prefix);


$settings = getSettings($db, $prefix);
$bilingualism = $settings['bilingualism'];

$language_code = getLanguageSetting($db, $prefix);
$translations = getTranslations($db, $prefix, $language_code);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['user_role'];

} else {
    header("Location: 404.php");
    exit;
}

if (!checkUserRole($user_id, 'admin', $db, $prefix) && !checkUserRole($user_id, 'moderator', $db, $prefix)) {
    header("Location: 404.php");
    exit;
}

$postMenuId = isset($_GET['menu_id']) ? (int)$_GET['menu_id'] : 0;
$postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;


 //print_r($_GET);
 
$mode = $postId > 0 ? 'edit' : 'create';
$post = $mode === 'edit' ? getPostById($db, $prefix, $postId) : $postMenuId;
$existingTags = getExistingTags($db, $prefix);
$menuId = getMenuItems($db, $prefix);

if ($mode === 'create' || $post) {
?>
<div id="post-form-content"> 
    <form id="post-form">
    <div class="row">
    <div class="col-8 mb-2">
        <input type="hidden" name="mode" value="<?php echo $mode; ?>">
        <?php if ($mode === 'edit'): ?>
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        <?php endif; ?>
        <input type="hidden" id="role"  name="role" value="<?php echo $role; ?>">
        
            <div class="form-group">
            <label for="post_menu"><?php echo t("Menu");?></label>
            <select class="form-control" id="post_menu" name="post_menu" required>
            <?php foreach ($menuId as $menu) : ?>
                <option value="<?php echo $menu['id']; ?>" 
                <?php 
                    if ($mode === 'edit' && $post['menu_id'] === $menu['id']) {
                        echo 'selected';
                    } elseif ($mode === 'create' && $postMenuId === $menu['id']) {
                        echo 'selected';
                    }
                ?>>
                    <?php echo htmlspecialchars($menu['name']); ?>
                </option>
            <?php endforeach; ?>
            </select>
        </div>
                
        <div class="form-group">
            <label for="post_title"><?php echo t("Title");?></label>
            <input type="text" class="form-control" id="post_title" name="post_title" value="<?php echo $mode === 'edit' ? htmlspecialchars($post['title']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="post_content"><?php echo t("Content");?></label>
            <div class="toolbar">
            
            <button type="button" onclick="textBreak('post_content')" style="height: 35px; width: 35px;">br</button>
            <button type="button" onclick="paragraphText('post_content')" style="height: 35px; width: 35px;">p</button>
            <button type="button" onclick="boldText('post_content')" style="height: 35px; width: 35px;"><b>b</b></button>
            <button type="button" onclick="italicText('post_content')" style="height: 35px; width: 35px;"><i>i</i></button>
            <button type="button" onclick="underlineText('post_content')" style="height: 35px; width: 35px;"><u>u</u></button>
            <button type="button" onclick="markDel('post_content')" style="height: 35px;"><del>d<del></button>
            <button type="button" onclick="header1('post_content')" style="height: 35px;"><p>h1</p></button>
            <button type="button" onclick="header2('post_content')" style="height: 35px;"><p>h2</p></button>
            <button type="button" onclick="header3('post_content')" style="height: 35px;"><p>h3</p></button>
            <button type="button" onclick="hypertext('post_content')" style="height: 35px;"><p>url</p></button>
            <button type="button" onclick="listTextUl('post_content')" style="height: 35px;"><p>ul</p></button>
            <button type="button" onclick="listTextLi('post_content')" style="height: 35px;"><p>li</p></button>
            <button type="button" onclick="quotationMark('post_content')" style="height: 35px;"><p>„“</p></button>
            <button type="button" onclick="markOl('post_content')" style="height: 35px;"><p>1..</p></button>
            <button type="button" onclick="markSub('post_content')" style="height: 35px;"><sub>2</sub></button>
            <button type="button" onclick="markSup('post_content')" style="height: 35px;"><sup>4</sup></button>
            <button type="button" onclick="markHr('post_content')" style="height: 35px;"><p>__</p></button>
            <button type="button" onclick="markText('post_content')" style="height: 35px;"><p><mark>text</mark></p></button>
            <button type="button" onclick="selectImage('post_content')" style="height: 35px;"><img>img</img></button>
            <button type="button" onclick="previewPost('post_content')" style="padding: 0px;">
            <i class="fas fa-expand-arrows-alt" title="Preview"style="size:28px; color: #c2c2c2;"></i>
            </button>
        
            </div>
            <textarea class="form-control" contenteditable="true" id="post_content" name="post_content" rows="16" required><?php echo $mode === 'edit' ? htmlspecialchars_decode($post['content']) : ''; ?></textarea>
        </div>
<!-- Other language start-->
<?php if($bilingualism != 0): ?>
        <div class="form-group mb-2">
        <label for="post_status"><?php echo t("Next Language");?></label>
        <div class="accordion accordion-flush mb-3" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
            <?php echo t("Add content in another language");?>
            </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
            <div class="form-group mb-2">
            <label for="post_title"><?php echo t("Other languages Title");?></label>
            <input type="text" class="form-control" id="lang_post_title" name="lang_post_title" value="<?php echo $mode === 'edit' ? htmlspecialchars($post['lang_post_title']) : ''; ?>">
            </div>
            <div class="toolbar">
            
            <button type="button" onclick="textBreak('lang_post_content')" style="height: 35px; width: 35px;">br</button>
            <button type="button" onclick="paragraphText('lang_post_content')" style="height: 35px; width: 35px;">p</button>
            <button type="button" onclick="boldText('lang_post_content')" style="height: 35px; width: 35px;"><b>b</b></button>
            <button type="button" onclick="italicText('lang_post_content')" style="height: 35px; width: 35px;"><i>i</i></button>
            <button type="button" onclick="underlineText('lang_post_content')" style="height: 35px; width: 35px;"><u>u</u></button>
            <button type="button" onclick="markDel('lang_post_content')" style="height: 35px;"><del>d<del></button>
            <button type="button" onclick="header1('lang_post_content')" style="height: 35px;"><p>h1</p></button>
            <button type="button" onclick="header2('lang_post_content')" style="height: 35px;"><p>h2</p></button>
            <button type="button" onclick="header3('lang_post_content')" style="height: 35px;"><p>h3</p></button>
            <button type="button" onclick="hypertext('lang_post_content')" style="height: 35px;"><p>url</p></button>
            <button type="button" onclick="listTextUl('lang_post_content')" style="height: 35px;"><p>ul</p></button>
            <button type="button" onclick="listTextLi('lang_post_content')" style="height: 35px;"><p>li</p></button>
            <button type="button" onclick="quotationMark('lang_post_content')" style="height: 35px;"><p>„“</p></button>
            <button type="button" onclick="markOl('lang_post_content')" style="height: 35px;"><p>1..</p></button>
            <button type="button" onclick="markSub('lang_post_content')" style="height: 35px;"><sub>2</sub></button>
            <button type="button" onclick="markSup('lang_post_content')" style="height: 35px;"><sup>4</sup></button>
            <button type="button" onclick="markHr('lang_post_content')" style="height: 35px;"><p>__</p></button>
            <button type="button" onclick="markText('lang_post_content')" style="height: 35px;"><p><mark>text</mark></p></button>
            <button type="button" onclick="selectImage('lang_post_content')" style="height: 35px;"><img>img</img></button>
            <button type="button" onclick="previewPost('lang_post_content')" style="padding: 0px;">
            <i class="fas fa-expand-arrows-alt" title="Preview"style="size:28px; color: #c2c2c2;"></i>
            </button>
        
            </div>
            <textarea class="form-control"  contenteditable="true" id="lang_post_content" name="lang_post_content" rows="16"><?php echo $mode === 'edit' ? htmlspecialchars_decode($post['lang_post_content']) : ''; ?></textarea>
            </div>
            </div>
        </div>
        </div>
        </div>
        <?php endif; ?>
<!-- Other language end-->

        </div>
        <div class="col-4">
        <div class="form-group mb-2">
            
            <label for="post_tags" class="form-label"><?php echo t("Tags");?></label>
            <input type="text" class="form-control" id="post_tags" name="post_tags" value="<?php echo $mode === 'edit' ? htmlspecialchars($post['tags']) : ''; ?>"/>
            <div id="tag-suggestions" class="mt-2"></div>
            <p><?php echo t("Existing Tag's");?></p>
            <?php foreach ($existingTags as $tag): ?>
                <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($tag); ?></span>
                <?php endforeach; ?>
            </div>
            
        <div class="form-group mb-2">
            <label for="post_status"><?php echo t("Post status");?></label>
            <select class="form-control" id="post_status" name="post_status" required>
            <option value="draft" <?php echo $mode === 'edit' && $post['status'] === 'draft' ? 'selected' : ''; ?>><?php echo t("Draft");?></option>
            <option value="published" <?php echo $mode === 'edit' && $post['status'] === 'published' ? 'selected' : ''; ?>><?php echo t("Published");?></option>
            </select>
        </div>
        <div class="form-group mb-2">
        <label for="post_status"><?php echo t("SEO territory");?></label>
        <div class="accordion accordion-flush mb-3" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
            <?php echo t("Meta description (50 - symbols recommended 160)");?>
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
            <textarea class="form-control"  id="post_description" name="post_description" rows="5"><?php echo $mode === 'edit' ? htmlspecialchars_decode($post['description']) : ''; ?></textarea>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
            <?php echo t("Meta keywords");?>
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
            <textarea class="form-control"  id="post_keywords" name="post_keywords" rows="2"><?php echo $mode === 'edit' ? htmlspecialchars_decode($post['keywords']) : ''; ?></textarea>
            </div>
            </div>
        </div>
        </div>
        </div>
        <div class="form-group mt-2 mb-2">
            <label for="post_priority"><?php echo t("Seo Priority");?></label>
            <?php if (isset($post['priority'])){?>
            <input type="checkbox" class="form-control-ch" id="post_priority" name="post_priority" value="1" <?php  echo $mode === 'edit'  ? htmlspecialchars($post['priority'] && ($post['priority'] == 1) ? 'checked' : '') : ''  ; ?>>
          <?php } else { ?>
            <input type="checkbox" class="form-control-ch" id="post_priority" name="post_priority" value="1" <?php  echo $mode === 'edit'  ? htmlspecialchars($post['priority']) : '' && (isset($post['priority']) && $post['priority'] == 1 ? 'checked' : ''); ?>>
            <?php 
                }
                ?>

        </div>

    <button type="submit" class="btn btn-primary"><?php echo $mode === 'edit' ? t('Update Post') : t('Add Post'); ?></button>
    <button type="button" class="btn btn-secondary" id="cancel-post"><?php echo $mode === 'edit' ? t('Cancel') : t('Back'); ?></button>
 </div>
 </div>
</form>
</div>

<?php
} else {
    echo t('Post not found.');
}
?>
