let selectedOrdinanceId = null;

document.addEventListener("DOMContentLoaded", () => {
  const descriptions = document.querySelectorAll('.ordinance-description-text');
  const textMaxLength = 100;

  descriptions.forEach(desc => {
    const fullText = desc.innerText;
    desc.setAttribute('data-full-text', fullText);
    if (fullText.length > textMaxLength) {
      const truncatedText = fullText.substring(0, textMaxLength) + '...';
      desc.innerText = truncatedText;
      desc.setAttribute('data-truncated-text', truncatedText);
    }
  });
});

document.getElementById('emailForm').addEventListener('submit', function (event) {
  event.preventDefault();
  const email = document.getElementById('emailInput').value;

  if (email && selectedOrdinanceId) {
    fetch(`/ordinances/download/${selectedOrdinanceId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email }),
    })
      .then(response => {
        if (!response.ok) {
          return response.json().then(data => {
            throw new Error(data.error || 'An error occurred');
          });
        }
        return response.blob();
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        const uniqueId = Math.random().toString(36).substr(2, 9);
        a.download = `ordinance_${uniqueId}.pdf`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        alert(error.message);
      });

  } else {
    alert('Please provide a valid email.');
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');

  // Toggle the sidebar when the button is clicked
  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');  // Toggle visibility of sidebar
    sidebarToggle.classList.toggle('opened'); // Toggle the button's active state
    sidebarToggle.classList.toggle('closed'); // Change button state for closing
  });
});

function toggleDescription(event) {
  const descriptionText = event.target.previousElementSibling;
  const fullText = descriptionText.getAttribute('data-full-text');
  const truncatedText = descriptionText.getAttribute('data-truncated-text');

  if (descriptionText.innerText === truncatedText) {
    descriptionText.innerText = fullText;
    event.target.innerText = 'See less';
  } else {
    descriptionText.innerText = truncatedText;
    event.target.innerText = 'See more';
  }
}

function openModal(ordinanceId) {
  selectedOrdinanceId = ordinanceId;
  document.getElementById('emailModal').classList.remove('hidden');
}

function closeModal() {
  document.getElementById('emailModal').classList.add('hidden');
  selectedOrdinanceId = null;
}

