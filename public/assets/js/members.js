// Add CSS to make truncated descriptions work well
document.addEventListener("DOMContentLoaded", () => {
  const descriptions = document.querySelectorAll('.member-description-text');
  const textMaxLength = 200;
  
  // Debugging: Log the descriptions to check if they are being rendered
  descriptions.forEach(desc => {
      const fullText = desc.innerText;
      desc.setAttribute('data-full-text', fullText);
      if (fullText.length > textMaxLength) {
          const truncatedText = fullText.substring(0, textMaxLength) + '...';
          desc.innerText = truncatedText;
          // Store the full and truncated text in data attributes
          desc.setAttribute('data-truncated-text', truncatedText);
      }
  });
});

// Function to toggle description visibility
function toggleDescription(event) {
  const descriptionText = event.target.previousElementSibling; // Get the description text span
  const fullText = descriptionText.getAttribute('data-full-text'); // Get the full text from data attribute
  const truncatedText = descriptionText.getAttribute('data-truncated-text'); // Get the truncated text from data attribute

  console.log(fullText);
  console.log(truncatedText);

  if (truncatedText === null) {
    descriptionText.innerText = fullText;    
    event.target.innerText = '';
    return;
  }

  if (descriptionText.innerText === truncatedText) {
    descriptionText.innerText = fullText;    
    event.target.innerText = 'See less';
  } else {
    descriptionText.innerText = truncatedText;
    event.target.innerText = 'See more';
  }
}

// Function to open image in full-screen modal
function openModal(event) {
  const imageUrl = event.target.getAttribute('data-member-image');
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');
  modalImage.src = imageUrl;
  modal.classList.remove('hidden');
}

// Function to close the modal
function closeModal() {
  const modal = document.getElementById('imageModal');
  modal.classList.add('hidden');
}
