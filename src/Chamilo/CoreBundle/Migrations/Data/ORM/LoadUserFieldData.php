<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Data\ORM;

use Chamilo\CoreBundle\Entity\ExtraField;
use Chamilo\CoreBundle\Entity\ExtraFieldOptions;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;

/**
 * Class LoadUserFieldData
 * @package Chamilo\CoreBundle\DataFixtures\ORM
 */
class LoadUserFieldData extends AbstractFixture implements
    ContainerAwareInterface,
    OrderedFixtureInterface,
    VersionedFixtureInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return '2.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 7;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Saving user fields
        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('legal_accept');
        $userField->setDisplayText('Legal');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setExtraFieldType(1);
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setVariable('already_logged_in');
        $userField->setDisplayText('Already logged in');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('update_type');
        $userField->setDisplayText('Update script type');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(10);
        $userField->setVariable('tags');
        $userField->setDisplayText('tags');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('rssfeeds');
        $userField->setDisplayText('RSS');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('legal_accept');
        $userField->setDisplayText('Legal');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('dashboard');
        $userField->setDisplayText('Dashboard');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('timezone');
        $userField->setDisplayText('Timezone');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $notifyInvitation = new ExtraField();
        $notifyInvitation->setFieldType(ExtraField::USER_FIELD_TYPE);
        $notifyInvitation->setExtraFieldType(4);
        $notifyInvitation->setVariable('mail_notify_invitation');
        $notifyInvitation->setDisplayText('MailNotifyInvitation');
        $notifyInvitation->setVisibleToSelf(1);
        $notifyInvitation->setChangeable(1);
        $notifyInvitation->setDefaultValue(1);
        $manager->persist($notifyInvitation);

        $notifyMessage = new ExtraField();
        $notifyMessage->setFieldType(ExtraField::USER_FIELD_TYPE);
        $notifyMessage->setExtraFieldType(4);
        $notifyMessage->setVariable('mail_notify_message');
        $notifyMessage->setDisplayText('MailNotifyMessage');
        $notifyMessage->setVisibleToSelf(1);
        $notifyMessage->setChangeable(1);
        $notifyMessage->setDefaultValue(1);
        $manager->persist($notifyMessage);

        $notifyGroup = new ExtraField();
        $notifyGroup->setFieldType(ExtraField::USER_FIELD_TYPE);
        $notifyGroup->setExtraFieldType(4);
        $notifyGroup->setVariable('mail_notify_group_message');
        $notifyGroup->setDisplayText('MailNotifyGroupMessage');
        $notifyGroup->setVisibleToSelf(1);
        $notifyGroup->setChangeable(1);
        $notifyGroup->setDefaultValue(1);
        $manager->persist($notifyGroup);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('user_chat_status');
        $userField->setDisplayText('User chat status');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType(ExtraField::USER_FIELD_TYPE);
        $userField->setExtraFieldType(1);
        $userField->setVariable('google_calendar_url');
        $userField->setDisplayText('Google Calendar URL');
        $userField->setVisibleToSelf(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        // First
        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyInvitation);
        $userFieldOption->setValue(1);
        $userFieldOption->setDisplayText('AtOnce');
        $userFieldOption->setOptionOrder(1);
        $manager->persist($userFieldOption);

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyInvitation);
        $userFieldOption->setValue(8);
        $userFieldOption->setDisplayText('Daily');
        $userFieldOption->setOptionOrder(2);
        $manager->persist($userFieldOption);

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyInvitation);
        $userFieldOption->setValue(0);
        $userFieldOption->setDisplayText('No');
        $userFieldOption->setOptionOrder(3);
        $manager->persist($userFieldOption);

        // Second
        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyGroup);
        $userFieldOption->setValue(1);
        $userFieldOption->setDisplayText('AtOnce');
        $userFieldOption->setOptionOrder(1);
        $manager->persist($userFieldOption);

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyGroup);
        $userFieldOption->setValue(8);
        $userFieldOption->setDisplayText('Daily');
        $userFieldOption->setOptionOrder(2);
        $manager->persist($userFieldOption);

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyGroup);
        $userFieldOption->setValue(0);
        $userFieldOption->setDisplayText('No');
        $userFieldOption->setOptionOrder(3);
        $manager->persist($userFieldOption);

        // Third

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyMessage);
        $userFieldOption->setValue(1);
        $userFieldOption->setDisplayText('AtOnce');
        $userFieldOption->setOptionOrder(1);
        $manager->persist($userFieldOption);

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyMessage);
        $userFieldOption->setValue(8);
        $userFieldOption->setDisplayText('Daily');
        $userFieldOption->setOptionOrder(2);
        $manager->persist($userFieldOption);

        $userFieldOption = new ExtraFieldOptions();
        $userFieldOption->setField($notifyMessage);
        $userFieldOption->setValue(0);
        $userFieldOption->setDisplayText('No');
        $userFieldOption->setOptionOrder(3);
        $manager->persist($userFieldOption);

        $manager->flush();
    }

    /**
     * @return \FOS\UserBundle\Model\UserManagerInterface
     */
    public function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}
