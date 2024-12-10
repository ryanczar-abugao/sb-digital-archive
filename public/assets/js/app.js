document.addEventListener("DOMContentLoaded", function () {
  const splashScreen = document.getElementById('splash-screen');
  setTimeout(() => {
    splashScreen.style.opacity = '0';
    splashScreen.style.transition = 'opacity 0.5s ease';
    setTimeout(() => splashScreen.style.display = 'none', 500);
  }, 1000); // Adjust the delay as needed
});

function toggleText(button) {
  const paragraph = button.previousElementSibling;

  if (!paragraph) {
      console.error('Paragraph element not found.');
      return;
  }

  const fullText = paragraph.getAttribute('data-full-text');
  console.log('Full text:', fullText);  // Check if full text is being accessed

  if (!fullText || fullText.trim() === '') {
      console.error('Full text data is not available.');
      return;
  }

  // Decode and toggle content
  const decodedFullText = decodeUnicode(fullText);
  const isTruncated = paragraph.classList.contains('truncate-text');

  if (isTruncated) {
      paragraph.classList.remove('truncate-text');
      paragraph.innerHTML = decodedFullText;  // Insert decoded HTML
      button.innerText = 'See Less';
  } else {
      paragraph.classList.add('truncate-text');
      const truncatedText = truncateHTML(decodedFullText, 150);  // Truncate HTML
      paragraph.innerHTML = truncatedText;
      button.innerText = 'See More';
  }
}


// Function to decode Unicode escaped string
function decodeUnicode(str) {
  return str.replace(/\\u[\dA-F]{4}/gi, function(match) {
      return String.fromCharCode(parseInt(match.replace(/\\u/, ''), 16));
  });
}

function truncateHTML(html, maxLength) {
  const div = document.createElement('div');
  div.innerHTML = html;

  let content = '';
  let charCount = 0;

  function traverseNodes(node) {
      if (charCount >= maxLength) return;

      if (node.nodeType === Node.TEXT_NODE) {
          const remaining = maxLength - charCount;
          content += node.textContent.slice(0, remaining);
          charCount += node.textContent.length;
      } else if (node.nodeType === Node.ELEMENT_NODE) {
          content += `<${node.tagName.toLowerCase()}>`;
          node.childNodes.forEach(traverseNodes);
          content += `</${node.tagName.toLowerCase()}>`;
      }
  }

  div.childNodes.forEach(traverseNodes);

  if (charCount > maxLength) {
      content += '...';
  }

  return content;
}
