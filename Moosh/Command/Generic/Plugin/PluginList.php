<?php
/**
 * moosh - Moodle Shell
 *
 * @copyright  2012 onwards Tomasz Muras
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Kacper Golewski <k.golewski@gmail.com>
 */

namespace Moosh\Command\Generic\Plugin;
use Moosh\MooshCommand;

class PluginList extends MooshCommand
{
    public function __construct()
    {
        parent::__construct('list', 'plugin');

        $this->addArgument('query');
        $this->addOption('p|path:', 'path to plugins.json file', home_dir() . '/.moosh/plugins.json');    
    }

    public function execute()
    {
        $readablemoodleversions = array(
            "17" => "2.7",
            "15" => "2.6",
            "13" => "2.5",
            "11" => "2.4",
            "10" => "2.3",
            "8" => "2.2",
            "1" => "2.1",
            "2" => "2.0",
            "3" => "1.9"
        );
        $found = array();
        $json_path = $this->expandedOptions['path'];
        $query = $this->arguments[0];

        $json_file = file_get_contents($json_path);

        if($json_file === false) {
            die("Can't read json file");
        }

        $json_data = json_decode($json_file);
        echo "\n";

        foreach ($json_data as $name => $plugin_data) {
            if (stristr($name, $query) !== false || stristr($plugin_data->full_name, $query) !== false) {
                echo $plugin_data->full_name . " (" . $name . ")\n";
                echo "\t" . $plugin_data->short_description . "\n";
                echo "\t" . "Supported Moodle versions: ";
                foreach ($plugin_data->moodle_versions as $version => $version_data) {
                    echo $readablemoodleversions[$version] . " ";
                }
                echo "\n\n";
            } 
        }
    }
}
