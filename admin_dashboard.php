<?php
$pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "");

// Handle submitted answer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'], $_POST['answer'])) {
    $questionId = $_POST['question_id'];
    $answer = trim($_POST['answer']);

    // Get the original question
    $stmt = $pdo->prepare("SELECT user_question FROM unanswered_questions WHERE id = ?");
    $stmt->execute([$questionId]);
    $questionRow = $stmt->fetch();

    if ($questionRow) {
        $question = $questionRow['user_question'];

        // Insert into faq table
        $insert = $pdo->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
        $insert->execute([$question, $answer]);

        // Delete from unanswered list
        $delete = $pdo->prepare("DELETE FROM unanswered_questions WHERE id = ?");
        $delete->execute([$questionId]);

        echo "<p style='color:green;'>Saved & taught the bot: \"$question\" ✅</p>";
    }
}

// Get all unanswered questions
$unanswered = $pdo->query("SELECT * FROM unanswered_questions ORDER BY asked_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard – Chatbot Trainer</title>
    <style>
        body { font-family: Arial; max-width: 700px; margin: auto; padding: 2rem; }
        .box { border: 1px solid #ccc; padding: 1rem; margin-bottom: 1rem; border-radius: 6px; }
        input, textarea, button { width: 100%; margin-top: 0.5rem; padding: 0.5rem; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>🧠 Train Your Chatbot – Unanswered Questions</h2>

    <?php if (count($unanswered) === 0): ?>
        <p>No new questions to answer.</p>
    <?php endif; ?>

    <?php foreach ($unanswered as $row): ?>
        <div class="box">
            <p><strong>❓ Question:</strong> <?= htmlspecialchars($row['user_question']) ?></p>
            <form method="POST">
                <input type="hidden" name="question_id" value="<?= $row['id'] ?>">
                <label>✏️ Your Answer:</label>
                <textarea name="answer" required rows="3"></textarea>
                <button type="submit">✅ Save & Teach Bot</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
