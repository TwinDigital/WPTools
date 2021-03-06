<?php

namespace TwinDigital\WPTools;

use WP_UnitTestCase;

/**
 * Class PluginRequirementsTest
 */
class PluginRequirementsTest extends WP_UnitTestCase
{

    /**
     * Plugins that come pre-installed with WordPress.
     *
     * @var array $preinstalledPlugins
     */
    public $preinstalledPlugins = [
        'Akismet Anti-Spam',
        'Hello Dolly',
    ];

    /**
     * Test adding a plugin-requirement.
     * @covers \TwinDigital\WPTools\PluginRequirements::checkPluginInstalled()
     * @covers \TwinDigital\WPTools\PluginRequirements::checkRequiredPlugins()
     * @covers \TwinDigital\WPTools\PluginRequirements::addPluginRequirement()
     * @covers \TwinDigital\WPTools\PluginRequirements::removePluginRequirement()
     * @return void
     */
    public function testPluginRequirements()
    {
        PluginTools::refreshLoadedPlugins();
        // Add plugin to requirements-list, first time should be true,
        // second time false because requirement already in list.
        $this->assertTrue(
            PluginRequirements::addPluginRequirement(
                'Hello Dolly',
                '0.0.0',
                '0.0.0',
                true
            ),
            'Could not add fake requirements'
        );
        $this->assertFalse(
            PluginRequirements::addPluginRequirement(
                'Hello Dolly',
                '0.0.0',
                '0.0.0',
                true
            ),
            'Check for existing requirement failed'
        );
        $this->assertTrue(
            PluginRequirements::addPluginRequirement(
                'WPTools',
                '0.0.0',
                '0.0.0',
                true
            ),
            'Could not add fake requirements'
        );
        // Testing requiredPlugins should be false, since we set fake version-numbers.
        $this->assertFalse(
            PluginRequirements::checkRequiredPlugins(),
            'Requirement should not be met unless version number of Hello Dolly is 0.0.0'
        );
        $this->assertTrue(PluginRequirements::removePluginRequirement('WPTools'));

        $this->assertTrue(PluginRequirements::removePluginRequirement('Hello Dolly'));
        $this->assertFalse(PluginRequirements::removePluginRequirement('Hello Dolly'));
        $this->assertTrue(PluginRequirements::checkRequiredPlugins());
        activate_plugin('hello-dolly/hello.php');
        PluginTools::refreshLoadedPlugins();
        $this->assertTrue(
            PluginRequirements::addPluginRequirement(
                'Hello Dolly',
                '0.0.0',
                '999.999.999',
                false
            )
        );
        $this->assertTrue(PluginRequirements::checkRequiredPlugins(), 'Hello Dolly should not be active yet.');
        $this->assertTrue(PluginRequirements::removePluginRequirement('Hello Dolly'));
        $this->assertTrue(
            PluginRequirements::addPluginRequirement(
                'Hello Dolly',
                '0.0.0',
                '999.999.999',
                true
            )
        );
        $this->assertFalse(PluginRequirements::checkRequiredPlugins(), 'Hello Dolly should not be active yet.');
    }
}
