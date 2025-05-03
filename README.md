# 🤖 AI-Powered Customer Support Chatbot

A smart, PHP-based chatbot designed to handle customer support on business websites. It answers frequently asked questions, connects to a backend database, and can escalate conversations to a human manager when needed. The chatbot is structured for easy extension and future learning capabilities.

---

## 🎯 Features

- ✅ Responds to common queries (hours, pricing, refund policy, etc.)
- 🔁 Escalates to a manager if the question is too complex
- 🗃️ Connects to a backend database for dynamic answers
- 📶 Lightweight and responsive interface
- 🧠 Modular design prepared for future AI improvements
- 🔒 No user memory required — session-based simplicity

---

## 🗂️ Project Structure

```bash
ai-chatbot/
├── index.php              # Chat UI and logic
├── chatHandler.php        # Backend PHP handler
├── db/
│   └── chatbot.db         # SQLite or MySQL database
├── assets/
│   ├── css/
│   └── js/
├── includes/
│   └── responses.php      # Predefined response logic
└── README.md              # Project documentation
```

🛠️ Tech Stack
. PHP – Core chatbot logic and request handling
. JavaScript (AJAX) – Sends and receives messages in real-time
. HTML/CSS – Frontend interface
. MySQL/SQLite – Backend database for response storage

⚙️ Setup Instructions
1. Clone the repository
```bash
git clone https://github.com/your-username/ai-chatbot.git
```

2. Configure the database
. Import or create the chatbot table with common questions and answers
3. Start a local PHP server
```bash
php -S localhost:8000

```
4. Access the chatbot
. Open your browser and go to: http://localhost:8000







