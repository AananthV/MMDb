<script type="text/javascript">
      let restricted_pages = [
        'Dashboard',
        'Responses'
      ];

      function logout() {
        if(localStorage.getItem('JWT') != null) {
          localStorage.removeItem('JWT');
          toggle_auth_buttons();
          if(restricted_pages.includes(document.title)) {
            window.location = "<?php echo FRONTEND; ?>";
          }
        }
      }
</script>
