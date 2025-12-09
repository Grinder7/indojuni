@auth
    <style>
        .chatbot-assistant-message {
            background-color: #f1f1f1;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            max-width: 80%;
            width:fit-content;
            word-wrap: break-word;
            color: black;
        }

        .chatbot-user-message {
            background-color: #007bff;
            color: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            width:fit-content;
            max-width: 80%;
            word-wrap: break-word;
            margin-left: auto;
            color: white;
            white-space-collapse: preserve;
        }

        .chatbot-assistant-message p {
            margin: 0;
            text-align: left;
        }
        .chatbot-user-message p {
            margin: 0;
            text-align: left;
        }


        .clamp {
            top: min(var(--desired-top, 0px), 75px);
        }

        #outer_box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column-reverse;
            align-items: flex-end;
        }

        /* Chatbot popup appears above the button */
        #chatbot-popup {
            position:absolute;
            bottom: 90px;
            right: 0;
            max-width: 450px;
            max-height: calc(65vh - 5em);
            width:85vw;
            height: 700px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1001;
            margin-bottom:1.5em;
        }

        #chatbot-send {
            min-width:3em;
        }
        .hide-scrollbar {
            scrollbar-width: none;
        } 
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        } 
        .hide-scrollbar:focus {
            scrollbar-width: none;
        }

        .hide-scrollbar:focus::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div>
        <div id="outer_box">
            <div id="chatbot-btn">
                <button id="chatbot-toggle"
                    style="border: none; background: blue; cursor: pointer; padding:0.75rem; border-radius: 50%;">
                    <i class="fa-solid fa-comment" style="color: white; font-size: 1.5rem;"></i>
                </button>
            </div>
            {{-- Chatbot Popup UI --}}
            <div id="chatbot-popup" class="rounded border" style="font-size:1em;">
                <div class="d-flex justify-content-between align-items-center border-bottom p-2"
                    style="background: #007bff; color: white;">
                    <h5 class="mb-0">Virtual Assistant</h5>
                    <div class="d-flex align-items-center">
                        <button id="chatbot-clear"
                            style="border: none; background: transparent; color: white; font-size: 1rem; cursor: pointer; margin-right: 0.125rem;"><i
                                class="fa-solid fa-eraser"></i></button>
                        <button id="chatbot-close"
                            style="border: none; background: transparent; color: white; font-size: 1.1rem; cursor: pointer;"><i
                                class="fa-solid fa-times"></i></button>
                    </div>
                </div>
                <div id="chatbot-messages" class="d-flex flex-column bg-body-tertiary p-2 hide-scrollbar"
                    style="height: calc(100% - 50px); overflow-y: auto; scroll-behavior: smooth;">
                </div>
                <div class="input-group" style="position:relative; flex-wrap:wrap;align-items: stretch;">
                    <textarea id="chatbot-input" type="text" class="form-control hide-scrollbar" placeholder="Ketik pesan anda" rows=2 style="margin:0px;"></textarea>
                    <button class="btn btn-primary" type="button" id="chatbot-send"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
    <script>
        const chatInput = document.getElementById('chatbot-input');

        chatInput.addEventListener('click', () => {
            setTimeout(() => chatInput.focus(), 50);
        });


        function appendChatDOM(role, content, messageElem = null) {
            const chatArea = document.getElementById('chatbot-messages');
            if (messageElem === null) {
                messageElem = document.createElement('p');
            }

            if (role === 'assistant') {
                messageElem.className = 'chatbot-assistant-message';
                messageElem.innerHTML = DOMPurify.sanitize(marked.parse(content.trim() || '')).trim();
            } else if (role === 'user') {
                messageElem.className = 'chatbot-user-message';
                messageElem.textContent = content.trim();
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
            chatInput.disabled = true;
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
            chatInput.disabled = false;
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
            const message = chatInput.value.trim();
            if (message) {
                chatInput.value = '';
                sendMessage(message);
            }
        })
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chatbot-send').click();
            }
        });
        document.getElementById('chatbot-clear').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus riwayat obrolan?')) {
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
