<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * All the steps to restore block_google_search are defined here.
 *
 * @package     block_google_search
 * @category    backup
 * @copyright   2024 Daniel Castro
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// More information about the backup process: {@link https://docs.moodle.org/dev/Backup_API}.
// More information about the restore process: {@link https://docs.moodle.org/dev/Restore_API}.

/**
 * Defines the structure step to restore one google_search block.
 */
class restore_google_search_block_structure_step extends restore_structure_step {

    /**
     * Defines the structure to be restored.
     */
    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('elt', '/path/to/file');

        return $paths;
    }

    /**
     * Processes the elt restore data.
     *
     * @param array $data Parsed element data.
     */
    protected function process_elt($data) {
        global $DB;

        return $data;
    }

    /**
     * Defines post-execution actions.
     */
    protected function after_execute() {

        return;
    }
}
