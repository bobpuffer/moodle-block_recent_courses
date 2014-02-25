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
    public function init() {
        $this->title = get_string('pluginname', 'block_recent_courses');
    }

    public function has_config() {
        return true;
    }

    public function get_content() {
        global $USER, $CFG, $DB, $OUTPUT, $COURSE;
        $userid = $USER->id;
        if ($this->content !== null) {
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
        $sql = "SELECT l.timeaccess, l.courseid, c.fullname FROM {user_lastaccess} l
                JOIN {course} c on c.id = l.courseid
                WHERE l.userid = $userid";
        $sortedcourses = $DB->get_records_sql($sql);
        arsort($sortedcourses);
        $maximum = 8;
        foreach ($sortedcourses as $accessed => $course) {
            $this->content->text .= '<a href="' . $CFG->wwwroot. '/course/view.php?id=' . $course->courseid . '">'
                    . $course->fullname . '</a><br />';
            $maximum--;
            if ($maximum < 1) {
                break;
            }
        }
        return $this->content;
    }
}


