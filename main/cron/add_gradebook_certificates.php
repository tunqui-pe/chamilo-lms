<?php
/* For licensing terms, see /license.txt */

/**
 * Adds gradebook certificates to gradebook_certificate table from users
 * who have achieved the requirements but have not reviewed them yet
 * @package chamilo.cron
 * @author Imanol Losada <imanol.losada@beeznest.com>
 */

require_once __DIR__.'/../inc/global.inc.php';

$em = Database::getManager();

// Get all categories and users ids from gradebook
$categoriesAndUsers = $em
    ->createQuery('
        SELECT DISTINCT ge.categoryId, gr.userId
        FROM ChamiloCoreBundle:GradebookResult gr
        JOIN ChamiloCoreBundle:GradebookEvaluation ge WITH gr.evaluationId = ge
    ')
    ->getResult();

foreach ($categoriesAndUsers as $categoryAndUser) {
    Category::register_user_certificate(
        $categoryAndUser['categoryId'],
        $categoryAndUser['userId']
    );
}
