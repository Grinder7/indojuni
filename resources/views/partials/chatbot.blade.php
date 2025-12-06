@auth
    <style>
        .chatbot-assistant-message {
            text-align: left;
            background-color: #f1f1f1;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            max-width: 80%;
            word-wrap: break-word;
            color: black;
        }

        .chatbot-assistant-message p {
            margin: 0;
        }

        .chatbot-user-message {
            text-align: right;
            background-color: #007bff;
            color: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            max-width: 80%;
            word-wrap: break-word;
            margin-left: auto;
            color: white;
        }
        .clamp {
            top: min(var(--desired-top, 0px), 75px);
        }
        #outer_box{
            position:fixed;
            bottom:30px;
            right:30px;
            top:12vh;
        }

    </style>
    <div>
        <div id="outer_box" class="d-flex flex-column-reverse align-items-end p-2">
            {{-- Chatbot Toggle Button --}}
            <div style="z-index: 1000;" id="chatot-btn">
                <button id="chatbot-toggle"
                    style="border: none; background: blue; cursor: pointer; padding:0.75rem; border-radius: 50%;"><i
                        class="fa-solid fa-comment" style="color: white; font-size: 1.5rem;"></i></button>
            </div>
            {{-- Chatbot Popup UI --}}
            <div id="chatbot-popup"
                style="display: none; margin-bottom: 3.5em; width: 300px; max-height: 500px; height:100%; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 1001; z-index: 999; "
                class="bottom-0 end-0 rounded border clamp">
                <div class="d-flex justify-content-between align-items-center border-bottom p-2"
                    style="background: #007bff; color: white;">
                    <h5 class="mb-0">Virtual Assistant</h5>
                    <div class="d-flex align-items-center">
                        <button id="chatbot-clear"
                            style="border: none; background: transparent; color: white; font-size: 1rem; cursor: pointer; margin-right: 0.125rem;"><i
                                class="fa-solid fa-arrow-rotate-right"></i></button>
                        <button id="chatbot-close"
                            style="border: none; background: transparent; color: white; font-size: 1.1rem; cursor: pointer;"><i
                                class="fa-solid fa-times"></i></button>
                    </div>
                </div>
                <div id="chatbot-messages" class="d-flex flex-column bg-body-tertiary p-2"
                    style="height: calc(100% - 50px); overflow-y: auto; scrollbar-width: thin; scrollbar-color: #ccc transparent; scroll-behavior: smooth;">
                </div>
                <div class="input-group">
                    <input id="chatbot-input" type="text" class="form-control" placeholder="Ketik pesan anda">
                    <button class="btn btn-primary" type="button" id="chatbot-send">Kirim</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
    <script>
        function appendChatDOM(role, content, messageElem = null) {
            const chatArea = document.getElementById('chatbot-messages');
            if (messageElem === null) {
                messageElem = document.createElement('p');
            }

            if (role === 'assistant') {
                messageElem.className = 'chatbot-assistant-message';
                messageElem.innerHTML = DOMPurify.sanitize(marked.parse(content || ''));
            } else if (role === 'user') {
                messageElem.className = 'chatbot-user-message';
                messageElem.textContent = content;
            } else {
                return;
            }

            chatArea.appendChild(messageElem);
            chatArea.scrollTop = chatArea.scrollHeight;
        }


        async function initializeChat() {
            appendChatDOM("assistant", "Halo! Ada yang bisa saya bantu hari ini?");
        }

        function sendMessage(message) {
            chats.push({
                role: 'user',
                content: message
            });
            const chatArea = document.getElementById('chatbot-messages');
            appendChatDOM('user', message);
            const assistantMessageElem = document.createElement('p');
            appendChatDOM('assistant', '[Sedang menghasilkan respons...]', assistantMessageElem);
            fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message
                })
            }).then((response) => {
                assistantMessageElem.remove();
                return response.json()
            }).then(data => {
                appendChatDOM('assistant', data.chat.content);
            }).catch(error => {
                console.error('Error:', error);
                assistantMessageElem.remove();
                appendChatDOM('assistant', 'Terjadi kesalahan: Tidak dapat menerima respons dari asisten.');
            });

        }

        const chats = @json(session('chat_fe_log', []));
        const chatArea = document.getElementById('chatbot-messages');
        initializeChat();
        chats.forEach(chat => {
            if (chat.role === 'assistant' || chat.role === 'user') {
                appendChatDOM(chat.role, chat.content, null);
            }
        });

        document.getElementById('chatbot-toggle').addEventListener('click', function() {
            const chatbotPopup = document.getElementById('chatbot-popup');
            if (chatbotPopup.style.display === 'none' || chatbotPopup.style.display === '') {
                chatbotPopup.style.display = 'block';
                chatArea.scrollTop = chatArea.scrollHeight; // Scroll to bottom
            } else {
                chatbotPopup.style.display = 'none';
            }
        });

        document.getElementById('chatbot-close').addEventListener('click', function() {
            const chatbotPopup = document.getElementById('chatbot-popup');
            chatbotPopup.style.display = 'none';
        });

        document.getElementById('chatbot-send').addEventListener('click', function() {
            const inputField = document.getElementById('chatbot-input');
            const message = inputField.value.trim();
            if (message) {
                inputField.value = '';
                sendMessage(message);
            }
        })
        document.getElementById('chatbot-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('chatbot-send').click();
            }
        });
        document.getElementById('chatbot-clear').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus riwayat obrolan?')) {
                const chatInput = document.getElementById('chatbot-input');
                chatInput.disabled = true;
                chatInput.placeholder = 'Menghapus Pesan...';
                const sendButton = document.getElementById('chatbot-send');
                sendButton.disabled = true;
                fetch("{{ route('chat.clear') }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                }).then((response) => {
                    return response.json()
                }).then(data => {
                    if (data.status === 'success') {
                        chats.length = 0; // Clear local chat history
                        chatArea.innerHTML = ''; // Clear chat area
                        initializeChat();
                        chatInput.disabled = false;
                        chatInput.placeholder = 'Ketik pesan anda';
                        sendButton.disabled = false;
                    } else {
                        alert('Gagal menghapus riwayat obrolan.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    chatInput.placeholder = 'Gagal menghapus pesan.';
                    alert('Gagal menghapus riwayat obrolan.');
                });
            }
        });
    </script>
@endauth
