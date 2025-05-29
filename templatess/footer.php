<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
  <strong>Copyright &copy; 2025 <span class="text-info">TB-MUTIARA</span></strong>. All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 6.2.4
  </div>
</footer>

<!-- REQUIRED SCRIPTS -->
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/chart.js/Chart.min.js"></script>
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/dist/js/pages/dashboard3.js"></script>

<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>

<script>
  $(function(){
      $('#tblData').DataTable();
  })

  const toggleBtn = document.getElementById('darkModeToggle');
  const body = document.body;
  const navbar = document.getElementById('navbar');
  const icon = toggleBtn.querySelector('i');

  function updateDarkMode(isDark) {
    if (isDark) {
      body.classList.add('dark-mode');
      navbar.classList.remove('navbar-light', 'bg-light');
      navbar.classList.add('navbar-dark', 'bg-dark');
      icon.classList.remove('fa-moon');
      icon.classList.add('fa-sun');
      localStorage.setItem('darkMode', 'on');
    } else {
      body.classList.remove('dark-mode');
      navbar.classList.remove('navbar-dark', 'bg-dark');
      navbar.classList.add('navbar-light', 'bg-light');
      icon.classList.remove('fa-sun');
      icon.classList.add('fa-moon');
      localStorage.setItem('darkMode', 'off');
    }
  }

  // Saat halaman load
  const savedMode = localStorage.getItem('darkMode');
  if (savedMode === 'on') {
    updateDarkMode(true);
  } else {
    updateDarkMode(false);
  }

  toggleBtn.addEventListener('click', () => {
    const isDark = !body.classList.contains('dark-mode');
    updateDarkMode(isDark);
  });

   // Saat halaman akan ditutup atau di-refresh, simpan posisi scroll-nya
  window.addEventListener("beforeunload", function () {
    localStorage.setItem("scrollPosition", window.scrollY);
  });

  // Saat halaman selesai dimuat, kembalikan posisi scroll-nya
  window.addEventListener("load", function () {
    const scrollPosition = localStorage.getItem("scrollPosition");
    if (scrollPosition !== null) {
      window.scrollTo(0, parseInt(scrollPosition));
    }
  });

</script>

</body>
</html>