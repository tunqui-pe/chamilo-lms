<?php
// bundles to add/remove
/*
//new APY\DataGridBundle\APYDataGridBundle(),
//new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
//new JMS\SerializerBundle\JMSSerializerBundle($this),
// CMF Integration
//new Sylius\Bundle\AttributeBundle\SyliusAttributeBundle(),
//new Symfony\Cmf\Bundle\SearchBundle\CmfSearchBundle(),
//new Symfony\Cmf\Bundle\BlogBundle\CmfBlogBundle(),
//new Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle(),
//new Chamilo\TimelineBundle\ChamiloTimelineBundle()
// Chamilo course tool
//new Chamilo\NotebookBundle\ChamiloNotebookBundle(),
//new JMS\TranslationBundle\JMSTranslationBundle(),
//new JMS\DiExtraBundle\JMSDiExtraBundle($this),
//new JMS\AopBundle\JMSAopBundle(),
new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
//new Chamilo\CmsBundle\ChamiloCmsBundle(),
*/
return [
    'Symfony\Bundle\FrameworkBundle\FrameworkBundle' => ['all' => true],

    'Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle' => ['all' => true],

    'Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle' => ['all' => true],
    'Doctrine\Bundle\DoctrineBundle\DoctrineBundle' => ['all' => true],
    'Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle' => ['all' => true],

    'Oro\Bundle\MigrationBundle\OroMigrationBundle' => ['all' => true],

    'Chamilo\CoreBundle\ChamiloCoreBundle' => ['all' => true],
    'Chamilo\ThemeBundle\ChamiloThemeBundle' => ['all' => true],
    'Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle' => ['all' => true],

    'Chamilo\SkillBundle\ChamiloSkillBundle' => ['all' => true],
    'Chamilo\CourseBundle\ChamiloCourseBundle' => ['all' => true],
    'Chamilo\AdminBundle\ChamiloAdminBundle' => ['all' => true],
    'Chamilo\FaqBundle\ChamiloFaqBundle' => ['all' => true],
    'Chamilo\ContactBundle\ChamiloContactBundle' => ['all' => true],
    'Chamilo\InstallerBundle\ChamiloInstallerBundle' => ['all' => true],
    'Chamilo\TicketBundle\ChamiloTicketBundle' => ['all' => true],

    'Chamilo\SettingsBundle\ChamiloSettingsBundle' => ['all' => true],
    'Chamilo\PageBundle\ChamiloPageBundle' => ['all' => true],
    'Chamilo\UserBundle\ChamiloUserBundle' => ['all' => true],
    'Chamilo\ClassificationBundle\ChamiloClassificationBundle' => ['all' => true],
    'Chamilo\MediaBundle\ChamiloMediaBundle' => ['all' => true],
    'Chamilo\NotificationBundle\ChamiloNotificationBundle' => ['all' => true],
    'Chamilo\TimelineBundle\ChamiloTimelineBundle' => ['all' => true],

    'FOS\UserBundle\FOSUserBundle' => ['all' => true],
    'FOS\RestBundle\FOSRestBundle' => ['all' => true],
    'FOS\JsRoutingBundle\FOSJsRoutingBundle' => ['all' => true],

    'Spy\TimelineBundle\SpyTimelineBundle' => ['all' => true],

    'Sonata\UserBundle\SonataUserBundle' => ['all' => true],
    'Sonata\EasyExtendsBundle\SonataEasyExtendsBundle' => ['all' => true],
    'Sonata\DatagridBundle\SonataDatagridBundle' => ['all' => true],
    'Sonata\CoreBundle\SonataCoreBundle' => ['all' => true],
    'Sonata\BlockBundle\SonataBlockBundle' => ['all' => true],
    'Sonata\AdminBundle\SonataAdminBundle' => ['all' => true],
    'Sonata\IntlBundle\SonataIntlBundle' => ['all' => true],
    'Sonata\TimelineBundle\SonataTimelineBundle' => ['all' => true],
    'Sonata\NotificationBundle\SonataNotificationBundle' => ['all' => true],
    'Sonata\MediaBundle\SonataMediaBundle' => ['all' => true],
    'Sonata\SeoBundle\SonataSeoBundle' => ['all' => true],
    'Sonata\CacheBundle\SonataCacheBundle' => ['all' => true],
    'Sonata\PageBundle\SonataPageBundle' => ['all' => true],
    'Sonata\FormatterBundle\SonataFormatterBundle' => ['all' => true],
    'Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle' => ['all' => true],
    'Sonata\ClassificationBundle\SonataClassificationBundle' => ['all' => true],
    'Symfony\Bundle\MonologBundle\MonologBundle' => ['all' => true],

    'Knp\Bundle\MenuBundle\KnpMenuBundle' => ['all' => true],
    'Symfony\Bundle\AsseticBundle\AsseticBundle' => ['all' => true],

    'Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle' => ['all' => true],
    'HWI\Bundle\OAuthBundle\HWIOAuthBundle' => ['all' => true],



    'winzou\Bundle\StateMachineBundle\winzouStateMachineBundle' => ['all' => true],
    'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle' => ['all' => true],
    'Sylius\Bundle\ResourceBundle\SyliusResourceBundle' => ['all' => true],
    'Oneup\FlysystemBundle\OneupFlysystemBundle' => ['all' => true],
    'Sylius\Bundle\FlowBundle\SyliusFlowBundle' => ['all' => true],
    'Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle' => ['all' => true],
    'Sylius\Bundle\SettingsBundle\SyliusSettingsBundle' => ['all' => true],
    'Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle' => ['all' => true],
    'Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle' => ['all' => true],
    'Knp\Bundle\MarkdownBundle\KnpMarkdownBundle' => ['all' => true],
    'Ivory\CKEditorBundle\IvoryCKEditorBundle' => ['all' => true],
    'Liip\ThemeBundle\LiipThemeBundle' => ['all' => true],
    'Liip\ImagineBundle\LiipImagineBundle' => ['all' => true],
    'FM\ElfinderBundle\FMElfinderBundle' => ['all' => true],
    'Symfony\Bundle\TwigBundle\TwigBundle' => ['all' => true],
    'Symfony\Bundle\SecurityBundle\SecurityBundle' => ['all' => true],
    'Symfony\Bundle\WebServerBundle\WebServerBundle' => ['dev' => true],
    'Lunetics\LocaleBundle\LuneticsLocaleBundle' => ['all' => true],
    'Symfony\Bundle\WebProfilerBundle\WebProfilerBundle' => ['dev' => true, 'test' => true],
    'Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle' => ['all' => true],
    'Symfony\Bundle\DebugBundle\DebugBundle' => ['dev' => true, 'test' => true],
];
