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