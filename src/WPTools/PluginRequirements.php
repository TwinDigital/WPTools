<?php

namespace TwinDigital\WPTools;

/**
 * Class PluginRequirements
 */
class PluginRequirements
{

    /**
     * The minimum required PHP-version.
     * @var string $phpMinVersion
     */
    public static $phpMinVersion;

    /**
     * The maximum required PHP-version.
     * @var string $phpMaxVersion
     */
    public static $phpMaxVersion;

    /**
     * Internal list of the required plugins that are added by `addPluginRequirement()`
     * @var array $requiredPlugins
     */
    protected static $requiredPlugins = [];

    /**
     * The minimum required WordPress-version.
     * @var string $wpMinVersion
     */
    public static $wpMinVersion;

    /**
     * The maximum required WordPress-version.
     * @var string $wpMaxVersion
     */
    public static $wpMaxVersion;

    /**
     * Add requirement for a plugin.
     * @param string $pluginName       The name of the plugin.
     * @param string $pluginMinVersion The minimum version (SemVer) of the plugin.
     * @param string $pluginMaxVersion The maximum version (SemVer) of the plugin.
     * @param bool   $activated        Wether the plugin should be activated or not.
     *
     * @return bool Wether the plugin was added to the requirements or not.
     */
    public static function addPluginRequirement(
        string $pluginName,
        string $pluginMinVersion,
        string $pluginMaxVersion,
        bool $activated = true
    ) {
        foreach (self::$requiredPlugins as $plugin) {
            if ($plugin['Name'] === $pluginName) {
                return false;
            }
        }
        self::$requiredPlugins[] = [
            'Name'       => $pluginName,
            'MinVersion' => $pluginMinVersion,
            'MaxVersion' => $pluginMaxVersion,
            'Active'     => $activated,
        ];

        return true;
    }

    /**
     * Removes a plugin from the requirements-list.
     * @param string $pluginName The name of the plugin.
     *
     * @return bool
     */
    public static function removePluginRequirement(string $pluginName): bool
    {
        $return = false;
        foreach (self::$requiredPlugins as $key => $plugin) {
            if ($plugin['Name'] === $pluginName) {
                unset(self::$requiredPlugins[$key]);
                $return = true;
            }
        }
        self::$requiredPlugins = array_values(self::$requiredPlugins);

        return $return;
    }

    /**
     * Checks the required plugins.
     * @return bool Wether all checks are successful.
     */
    public static function checkRequiredPlugins(): bool
    {
        $return = true;
        foreach (self::$requiredPlugins as $plugin) {
            $installed = self::checkPluginInstalled($plugin['Name']);
            if ($installed === false) {
                $return = false;
            } elseif ($installed === true) {
                if ($plugin['Active'] === true) {
                    $return = (bool)(
                        $plugin['Active'] === PluginTools::getPluginByTitleCaseInsensitive($plugin['Name'])['Active']
                    );
                }
            }
        }

        return $return;
    }

    /**
     * Checks wether a plugin is installed.
     * @param string $pluginName The name of the plugin.
     *
     * @return bool
     */
    public static function checkPluginInstalled(string $pluginName): bool
    {
        $return = false;
        if (is_array(PluginTools::getPluginByTitleCaseInsensitive($pluginName)) === true) {
            $return = true;
        }

        return $return;
    }
}
