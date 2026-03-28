<?php
require 'db.php';
try {
    // Insert Jobs
    $stmt = $pdo->prepare("INSERT INTO jobs (title, category, type, location, description, closing_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // 1. Waiter
    $stmt->execute([
        "Waiter / Waitress",
        "Front of House",
        "Full Time",
        "Addis Ababa",
        "We are looking for a friendly and observant Waiter/Waitress to join our lively team at Sheger Kurt. You will be taking orders, serving traditional Ethiopian delicacies and drinks, and ensuring our guests have a memorable premium experience.",
        date('Y-m-d H:i:s', strtotime('+30 days')),
        "Open"
    ]);
    $waiter_id = $pdo->lastInsertId();

    // 2. Manager
    $stmt->execute([
        "Restaurant Manager",
        "Management",
        "Full Time",
        "Addis Ababa",
        "Sheger Kurt is seeking an experienced Restaurant Manager to oversee daily operations, guide our exceptional staff, manage inventory, and maintain our high standards of quality and service. Minimum 4 years of hospitality management required.",
        date('Y-m-d H:i:s', strtotime('+15 days')),
        "Open"
    ]);
    $manager_id = $pdo->lastInsertId();

    // 3. F&B
    $stmt->execute([
        "F and B Coordinator",
        "Food & Beverage",
        "Full Time",
        "Addis Ababa",
        "Join us as a Food and Beverage Coordinator, where you will manage vendor relations, organize menu updates, ensure strict hygiene standards are met, and help craft the ultimate dining experience at Sheger Kurt.",
        date('Y-m-d H:i:s', strtotime('+20 days')),
        "Open"
    ]);
    $fb_id = $pdo->lastInsertId();

    // Insert Questions
    $q_stmt = $pdo->prepare("INSERT INTO job_questions (job_id, question, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Waiter Questions
    $q_stmt->execute([$waiter_id, "How would you handle a customer complaining that their food is cold?", "Tell them it's supposed to be cold.", "Ignore the complaint and walk away.", "Apologize sincerely and immediately take the dish back to the kitchen to fix it.", "Argue with the customer.", "C"]);
    $q_stmt->execute([$waiter_id, "What is the most important trait for a waiter?", "Being able to cook.", "Strong communication and a friendly attitude.", "Knowing how to use a computer.", "Being very fast but careless.", "B"]);

    // Manager Questions
    $q_stmt->execute([$manager_id, "If two staff members are having a strong disagreement during a busy shift, what do you do?", "Fire both of them immediately.", "Ignore it, they will figure it out.", "Pull them aside quickly privately to resolve the urgent issue so service isn't disrupted.", "Yell at them in front of customers.", "C"]);
    $q_stmt->execute([$manager_id, "What metric is most important for tracking restaurant profitability?", "The number of napkins used.", "Food Cost Percentage.", "The color of the menus.", "How many times the phone rings.", "B"]);

    // F&B Questions
    $q_stmt->execute([$fb_id, "What does FIFO stand for in inventory management?", "Fast In, Fast Out", "First In, First Out", "Food In, Food Out", "First In, Finally Out", "B"]);
    $q_stmt->execute([$fb_id, "Which temperature zone is considered the 'Danger Zone' for food storage?", "0°C to 4°C", "5°C to 60°C", "70°C to 100°C", "-18°C to 0°C", "B"]);

    echo "Successfully seeded 3 sample jobs and 6 exam questions!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
