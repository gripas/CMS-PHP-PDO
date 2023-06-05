
<?php //require_once  $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';?>
<footer class="footer bg-light py-3">
    <div class="container-fluid">
    <p class="text-center mb-0">
        <?php print $footer_text; ?>
    </p>
    </div>
</footer>

<script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/core/tools/js/content_redactor.js"></script>
<script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/core/tools/js/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/popperjs/popper.min.js"></script>
<script>
        $(document).ready(function() {

            $('a[data-page]').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');

                $('#admin-content').load(page, function(response, status, xhr) {
                    if (status === 'error') {
                        console.error('Klaida įkeliant puslapį:', xhr.status, xhr.statusText);
                    }
                });
            });
        });
   
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000); 

$(document).ready(function() {
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
  const content = document.getElementById("content");

  if (sidebar.classList.contains("sidebar-collapsed")) {
    sidebar.classList.remove("sidebar-collapsed");
    sidebar.classList.add("sidebar-expanded");
    content.classList.remove("col-custom");
    content.classList.add("col-md-10");
    content.classList.remove("main-content-collapsed-sidebar");
    content.classList.add("main-content-with-sidebar");
  } else {
    sidebar.classList.remove("sidebar-expanded");
    sidebar.classList.add("sidebar-collapsed");
    content.classList.remove("col-md-10");
    content.classList.add("col-custom");
    content.classList.remove("main-content-with-sidebar");
    content.classList.add("main-content-collapsed-sidebar");
  }

  if (content.classList.contains("col-custom")) {
    content.style.width = "calc(100% - 50px)"; 
  } else {
    content.style.width = "calc(100% - 250px)";
  }
    }

    $('#toggleSidebarBtn').on('click', toggleSidebar);
});

document.getElementById('settingsDropdown').addEventListener('click', function() {
  var submenu = document.getElementById('settingsSubmenu');
  if (submenu.style.display === "none") {
    submenu.style.display = "block";
  } else {
    submenu.style.display = "none";
  }
});
</script>
</body>
</html>
