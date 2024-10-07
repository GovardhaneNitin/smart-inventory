<script>
  document.addEventListener('DOMContentLoaded', function() {
    var profileDropdown = document.getElementById('profileDropdown');
    var dropdownMenu = document.querySelector('.dropdown-menu[aria-labelledby="profileDropdown"]');

    profileDropdown.addEventListener('click', function(event) {
      event.preventDefault();
      dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function(event) {
      if (!profileDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.remove('show');
      }
    });
  });
</script>