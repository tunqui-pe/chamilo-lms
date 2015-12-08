<?php
/* For license terms, see /license.txt */
use \Chamilo\CoreBundle\Entity\Skill;
use \Chamilo\CoreBundle\Entity\SkillRelUser;

//require_once '../inc/global.inc.php';

if (!api_is_platform_admin(false, true)) {
    api_not_allowed();
}

$entityManager = Database::getManager();
$skillRepo = $entityManager->getRepository('ChamiloCoreBundle:Skill');
$skillUserRepo = $entityManager->getRepository('ChamiloCoreBundle:SkillRelUser');
$user = $entityManager->find('ChamiloUserBundle:User', $_REQUEST['user']);

if (!$user) {
    Display::addFlash(
        Display::return_message(get_lang('NoUser'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$skills = $skillRepo->findBy([
    'status' => Skill::STATUS_ENABLED
]);

$skillsOptions = [];

foreach ($skills as $skill) {
    $skillsOptions[$skill->getId()] = $skill->getName();
}

$form = new FormValidator('assign_skill');
$form->addText('user_name', get_lang('UserName'), false);
$form->addSelect('skill', get_lang('Skill'), $skillsOptions);
$form->addHidden('user', $user->getId());
$form->addRule('skill', get_lang('ThisFieldIsRequired'), 'required');
$form->addTextarea('argumentation', get_lang('Argumentation'), ['rows' => 6]);
$form->applyFilter('argumentation', 'trim');
$form->addRule('argumentation', get_lang('ThisFieldIsRequired'), 'required');
$form->addButtonSave(get_lang('Save'));

if ($form->validate()) {
    $values = $form->exportValues();

    $skill = $skillRepo->find($values['skill']);

    if (!$skill) {
        Display::addFlash(
            Display::return_message(get_lang('SkillNotFound'), 'error')
        );

        header('Location: ' . api_get_self() . '?' . http_build_query(['user' => $user->getId()]));
        exit;
    }

    if ($user->hasSkill($skill)) {
        Display::addFlash(
            Display::return_message(
                sprintf(get_lang('TheUserXHasAlreadyAchievedTheSkillY'), $user->getCompleteName(), $skill->getName()),
                'warning'
            )
        );

        header('Location: ' . api_get_self() . '?' . http_build_query(['user' => $user->getId()]));
        exit;
    }

    $skillUser = new SkillRelUser();
    $skillUser->setUser($user);
    $skillUser->setSkill($skill);
    $skillUser->setArgumentation($values['argumentation']);
    $skillUser->setAcquiredSkillAt(new DateTime());
    $skillUser->setAssignedBy(0);

    $entityManager->persist($skillUser);
    $entityManager->flush();

    Display::addFlash(
        Display::return_message(
            sprintf(get_lang('SkillXAssignedToUserY'), $skill->getName(), $user->getCompleteName()),
            'success'
        )
    );

    header('Location: ' . api_get_path(WEB_PATH) . "badge/{$skill->getId()}/user/{$user->getId()}");
    exit;
}

$form->setDefaults(['user_name' => $user->getCompleteName()]);
$form->freeze(['user_name']);

//View
echo get_lang('AssignSkill');
echo $form->returnForm();

