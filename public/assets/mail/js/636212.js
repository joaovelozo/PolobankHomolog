document.addEventListener('DOMContentLoaded', function() {
  const chatForms = document.querySelectorAll('.chat-form');

  chatForms.forEach(function(form) {
    const chatInput = form.querySelector('.chat-input');
    const sendButton = form.querySelector('.chat-send-btn');
    const messagesContainer = form.closest('.card').querySelector('.chat-messages');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const messageText = chatInput.value.trim();
      if (messageText) {
        const messagesDiv = messagesContainer.querySelector('div');
        const currentTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        const newMessage = document.createElement('div');
        newMessage.className = 'd-flex mb-3 justify-content-end';
        newMessage.innerHTML = `
<div class="flex-grow-1 text-end">
<div class="bg-primary text-white p-3 rounded d-inline-block">
<p class="mb-1">${messageText}</p>
<small class="text-light">${currentTime}</small>
</div>
</div>
<img src="https://placehold.co/40x40" class="rounded-circle ms-3" alt="User Avatar">
`;

        messagesDiv.appendChild(newMessage);
        chatInput.value = '';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
      }
    });

    chatInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        form.dispatchEvent(new Event('submit'));
      }
    });
  });
});