@auth
    <style>
        .chatbot-agent-message {
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
    </style>
    <div>
        {{-- Chatbot Toggle Button --}}
        <div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
            <button id="chatbot-toggle"
                style="border: none; background: blue; cursor: pointer; padding:0.75rem; border-radius: 50%;"><i
                    class="fa-solid fa-comment" style="color: white; font-size: 1.5rem;"></i></button>
        </div>
        <div>
            {{-- Chatbot Popup UI --}}
            <div id="chatbot-popup"
                style="display: none; width: 350px; height: 450px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 1001; margin-bottom: 125px; margin-right: 2rem;"
                class="position-fixed bottom-0 end-0 rounded border">
                <div class="d-flex justify-content-between align-items-center border-bottom p-2"
                    style="background: #007bff; color: white;">
                    <h5 class="mb-0">Chatbot</h5>
                    <button id="chatbot-close"
                        style="border: none; background: transparent; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
                </div>
                <div id="chatbot-messages" class="d-flex flex-column bg-body-tertiary p-2"
                    style="height: calc(100% - 50px); overflow-y: auto; scrollbar-width: thin; scrollbar-color: #ccc transparent; scroll-behavior: smooth;">
                    {{-- Chat messages will be appended here --}}
                </div>
                <div class="input-group">
                    <input id="chatbot-input" type="text" class="form-control" placeholder="Type your message...">
                    <button class="btn btn-primary" type="button" id="chatbot-send">Send</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function appendChatDOM(role, content, messageElem = null) {
            const chatArea = document.getElementById('chatbot-messages');
            if (messageElem === null) {
                messageElem = document.createElement('p');
            }
            if (role === 'agent') {
                messageElem.className = 'chatbot-agent-message';
            } else if (role === 'user') {
                messageElem.className = 'chatbot-user-message';
            }
            messageElem.textContent = content;
            chatArea.appendChild(messageElem);
            chatArea.scrollTop = chatArea.scrollHeight; // Scroll to bottom
        }

        async function initializeChat() {
            const response = await fetch("{{ route('chat.init') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });
            const data = await response.json().catch(error => {
                console.error('Error:', error);
                return {
                    status: 'error',
                    chats: [{
                        role: 'agent',
                        content: 'Error: Unable to initialize chat.'
                    }]
                };
            });
            return data;
        }

        function sendMessage(message) {
            chats.push({
                role: 'user',
                content: message
            });
            const chatArea = document.getElementById('chatbot-messages');
            appendChatDOM('user', message);
            const agentMessageElem = document.createElement('p');
            appendChatDOM('agent', '[Generating Response...]', agentMessageElem);
            fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message
                })
            }).then((response) => {
                agentMessageElem.remove();
                return response.json()
            }).then(data => {
                appendChatDOM('agent', data.chat.content);
            }).catch(error => {
                console.error('Error:', error);
                agentMessageElem.remove();
                appendChatDOM('agent', 'Error: Unable to get response from agent.');
            });

        }

        const chats = @json(session('chats', []));
        if (chats.length === 0 || chats[0].role !== 'system') {
            initializeChat().then(response => {
                chats.push(...response.chats);
                const chatArea = document.getElementById('chatbot-messages');
                response.chats.forEach(chat => {
                    appendChatDOM(chat.role, chat.content);
                });
            })
        }
        const chatArea = document.getElementById('chatbot-messages');
        chats.forEach(chat => {
            const messageElem = document.createElement('p');
            if (chat.role === 'agent') {
                messageElem.className = 'chatbot-agent-message';
            } else if (chat.role === 'user') {
                messageElem.className = 'chatbot-user-message';
            }
            messageElem.textContent = chat.content;
            chatArea.appendChild(messageElem);
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
    </script>
@endauth
