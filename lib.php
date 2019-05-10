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
 * Library file for badge ladder plugin
 *
 * @package    local_bs_badge_ladder
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Some file imports.
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/badgeslib.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/adminlib.php');

//----------------------Button creation an display if the user has the authorization---------------------------------------------
function local_info_logs_extend_navigation ($nav) {
    global $PAGE, $DB, $USER;

     $sql = "SELECT * FROM mdl_role_assignments AS ra LEFT JOIN mdl_user_enrolments AS ue ON ra.userid = ue.userid LEFT JOIN mdl_role AS r ON ra.roleid = r.id LEFT JOIN mdl_context AS c ON c.id = ra.contextid LEFT JOIN mdl_enrol AS e ON e.courseid = c.instanceid AND ue.enrolid = e.id WHERE r.shortname=? AND ue.userid=? AND e.courseid=?";

    $resultAdmin = $DB->get_records_sql($sql, array('manager',$USER->id,$PAGE->course->id));
    $resultTeacher = $DB->get_records_sql($sql, array('teacher',$USER->id,$PAGE->course->id));
    $resultEditingTeacher = $DB->get_records_sql($sql, array('editingteacher',$USER->id,$PAGE->course->id));

    if ($resultAdmin ||$resultTeacher ||$resultEditingTeacher) { 
    	$url = new moodle_url('/course/report/course_log/index.php', array('id' => $PAGE->course->id));
	    $coursenode = $nav->find($PAGE->course->id, $nav::TYPE_COURSE);
	    $navtext =  get_string('title','local_info_logs');
	    $coursenode->add($navtext, $url,
	    navigation_node::TYPE_SETTING, null, 'viewinfolog', new pix_icon('i/hist',"hist"));
	}
}