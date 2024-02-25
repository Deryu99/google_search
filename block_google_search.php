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
 * Block pluginname is defined here.
 *
 * @package     block_google_search
 * @copyright   2024, Daniel Castro
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_google_search extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_google_search');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        // Check if instance is empty
        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $api_key = get_string('google_search_apikey', 'block_google_search');
        $search_engine_id = get_string('google_search_searchengineid', 'block_google_search');
        $search_query = urlencode('Moodle Blocks'); // Search query, use urls encode to handle special chars

        $url = "https://www.googleapis.com/customsearch/v1?key={$api_key}&cx={$search_engine_id}&q={$search_query}";

        // Make API request
        $response = file_get_contents($url);

        // Check if response was successful
        if ($response !== false) {
            // Decode the JSON response
            $decoded_response = json_decode($response);

            // Check if decoding was successful and if items exist in the response
            if ($decoded_response !== null && property_exists($decoded_response, 'items')) {
                $items = $decoded_response->items;
                $displayedResults = '';

                // Iterate through each item and extract relevant fields
                foreach ($items as $item) {
                    // Construct HTML for each result
                    $result_html = '<div class="search-result">';
                    $result_html .= '<h3>' . $item->title . '</h3>';
                    $result_html .= '<p>' . $item->snippet . '</p>';
                    $result_html .= '</div>';

                    // Append to the overall HTML string
                    $displayedResults .= $result_html;
                }

                // Display relevant aspects of JSON
                $this->content->text = '<div class="search-results">' . $displayedResults . '</div>';
            } else {
                // Handle case where items are not present in the response
                $this->content->text = 'No items found in API response';
            }
        } else {
            // Handle case where API request failed
            $this->content->text = json_encode(array('error' => 'Failed to retrieve data from API'));
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization()
    {
        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_google_search');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats()
    {
        return array(
            'all' => false,
            'course-view' => true,
            'course-view-social' => false,
        );
    }

}
