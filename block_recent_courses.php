<?php

// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'block_recent_courses', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   block_recent_courses
 * @copyright  2014 Bob Puffer  {@link http://katie.luther.edu/}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_recent_courses extends block_base {
    function init() {
        $this->title = get_string('pluginname','block_recent_courses');
    }

    function has_config() {
        return true;
    }

    function get_content() {
        global $USER, $CFG, $DB, $OUTPUT, $COURSE;
        $userid = $USER->id;
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }
	
		$mycourses = enrol_get_users_courses($userid, true, array('*'), 'sortorder ASC');
		$sortedcourses = array();
		$sortedcourses = $DB->get_records('user_lastaccess', array('userid' => $userid), null, 'timeaccess, courseid');
		arsort($sortedcourses);
		$maximum = 8;
		$this->content->text .=  '<div style="color:#999999;line-height:1.4em;font-weight:bold;font-size:.6em;">' . get_string('needtogotherefirst','block_recent_courses') . '</div>';
		foreach ($sortedcourses as $accessed => $course) {
			if (array_key_exists($course->courseid, $mycourses)) {
				$this->content->text .= '<a href="' . $CFG->wwwroot. '/course/view.php?id=' . $course->courseid . '">' . $mycourses[$course->courseid]->fullname . '</a><br />';
				$maximum--;
				if ($maximum < 1) {
					break;
				}
			}
		}
        return $this->content;
    }
}


