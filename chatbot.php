<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userMessage = trim($_POST['message'] ?? '');

    if (empty($userMessage)) {
        echo json_encode(['reply' => 'Please enter a message.']);
        exit;
    }

    // First try: Full-Text Search
    $stmt = $pdo->prepare("
        SELECT *, MATCH(question) AGAINST(:msg IN NATURAL LANGUAGE MODE) AS score
        FROM faq
        WHERE MATCH(question) AGAINST(:msg IN NATURAL LANGUAGE MODE)
        ORDER BY score DESC
        LIMIT 1
    ");
    $stmt->execute(['msg' => $userMessage]);
    $bestMatch = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($bestMatch && $bestMatch['score'] > 0) {
        $reply = $bestMatch['answer'];
        error_log("FTS Match score: " . $bestMatch['score']); // Debug
    } else {
        // Fallback: Try LIKE match (basic keyword match)
        $likeStmt = $pdo->prepare("SELECT * FROM faq WHERE question LIKE :likeMsg LIMIT 1");
        $likeStmt->execute(['likeMsg' => '%' . $userMessage . '%']);
        $likeMatch = $likeStmt->fetch(PDO::FETCH_ASSOC);

        if ($likeMatch) {
            $reply = $likeMatch['answer'];
            error_log("LIKE fallback match used."); // Debug
        } else {
            // No match found — log the question
            $log = $pdo->prepare("INSERT INTO unanswered_questions (user_question) VALUES (:msg)");
            $log->execute(['msg' => $userMessage]);

            $reply = "I'm not sure how to answer that right now, but I’ve noted it. A human will follow up soon.";
        }
    }

    echo json_encode(['reply' => $reply]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['reply' => 'Sorry, something went wrong.']);
}
?>
