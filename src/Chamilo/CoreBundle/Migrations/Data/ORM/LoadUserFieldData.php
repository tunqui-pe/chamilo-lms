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
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('legal_accept');
        $userField->setDisplayText('Legal');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setExtraFieldType(1);
        $userField->setFieldType('user');
        $userField->setVariable('already_logged_in');
        $userField->setDisplayText('Already logged in');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('update_type');
        $userField->setDisplayText('Update script type');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(10);
        $userField->setVariable('tags');
        $userField->setDisplayText('tags');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('rssfeeds');
        $userField->setDisplayText('RSS');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('legal_accept');
        $userField->setDisplayText('Legal');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('dashboard');
        $userField->setDisplayText('Dashboard');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('timezone');
        $userField->setDisplayText('Timezone');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $notifyInvitation = new ExtraField();
        $notifyInvitation->setFieldType('user');
        $notifyInvitation->setExtraFieldType(4);
        $notifyInvitation->setVariable('mail_notify_invitation');
        $notifyInvitation->setDisplayText('MailNotifyInvitation');
        $notifyInvitation->setVisible(1);
        $notifyInvitation->setChangeable(1);
        $notifyInvitation->setDefaultValue(1);
        $manager->persist($notifyInvitation);

        $notifyMessage = new ExtraField();
        $notifyMessage->setFieldType('user');
        $notifyMessage->setExtraFieldType(4);
        $notifyMessage->setVariable('mail_notify_message');
        $notifyMessage->setDisplayText('MailNotifyMessage');
        $notifyMessage->setVisible(1);
        $notifyMessage->setChangeable(1);
        $notifyMessage->setDefaultValue(1);
        $manager->persist($notifyMessage);

        $notifyGroup = new ExtraField();
        $notifyGroup->setFieldType('user');
        $notifyGroup->setExtraFieldType(4);
        $notifyGroup->setVariable('mail_notify_group_message');
        $notifyGroup->setDisplayText('MailNotifyGroupMessage');
        $notifyGroup->setVisible(1);
        $notifyGroup->setChangeable(1);
        $notifyGroup->setDefaultValue(1);
        $manager->persist($notifyGroup);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('user_chat_status');
        $userField->setDisplayText('User chat status');
        $userField->setVisible(0);
        $userField->setChangeable(0);
        $manager->persist($userField);

        $userField = new ExtraField();
        $userField->setFieldType('user');
        $userField->setExtraFieldType(1);
        $userField->setVariable('google_calendar_url');
        $userField->setDisplayText('Google Calendar URL');
        $userField->setVisible(0);
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
