<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Hook\Interfaces;

/**
 * Interface HookMyStudentsQuizTrackingEventInterface.
 *
 * @package Chamilo\CoreBundle\Hook\Interfaces
 */
interface HookMyStudentsQuizTrackingEventInterface extends HookEventInterface
{
    public function notifyTrackingHeader(): array;

    /**
     * @param int $quizId
     * @param int $studentId
     */
    public function notifyTrackingContent($quizId, $studentId): array;
}
