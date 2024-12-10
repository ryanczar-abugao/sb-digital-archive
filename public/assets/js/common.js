document.addEventListener('DOMContentLoaded', function() {
  const currentUrl = window.location.href;
  if (currentUrl.includes('/edit/')) {
    // Trigger the popover to open
    document.querySelector('#formBtn').click();
  }

  // mapping of url routes
  if (currentUrl.includes('/home/')) {
    document.querySelector('#formBtn').click();
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');
  const toggleButton = document.getElementById('sidebarToggle');

  toggleButton.addEventListener('click', () => {
      const isOpened = toggleButton.classList.toggle('opened');
      toggleButton.classList.toggle('closed', !isOpened);

      sidebar.classList.toggle('2xl:block', isOpened);
      content.classList.toggle('2xl:col-span-10', isOpened);
      content.classList.toggle('2xl:col-span-12', !isOpened);
  });
});

