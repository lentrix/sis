<?php
$modules['enrolment'] = array("home","profile","student","enrol","subjects","listings","gradesheets","view_gradesheet",
	"semesters","view_edit_student","view_edit_subject","datasets","classes","view_edit_class","student_grades",
	"enrolment_report","faculty_report","enrolist","transcript/transcript","graduates_report",
	"drop_out_filter","prospectus","prospectus_builder","view_edit_prospectus",
	"student_subjects_selector","promo_rep","transcript/view", "transcript/insert",
	"transcript/edit","transcript/delete", "transcript/pdf","transcript/grad", "transcript/thesis");
$modules['deans'] = array("home","profile","view_student","subjects","view_edit_subject",
	"classes","prospectus","view_edit_class","teachers","student_subjects",
	"view_teacher_classes","prospectus_builder","view_edit_prospectus",
        "evaluation","class_transfer","listings","rooms",
		"deans_list","exam_sched","drop_out_filter","student_grades",
		"transcript/transcript","transcript/view", "transcript/pdf","enrol");
$modules['billing'] = array("home","profile","ledger","passbook","view_student", "payunits","edit_payment",
	"accounts","payments","lab_fees","fees_gen","listings","class_density","units_report","stud_payments",
    "teachers_load_finance","icl","class_time_summary");
$modules['teachers'] = array("home","profile","teacher_classes","teacher_class_record","student_subjects","view_class",
    "view_gradesheet","referrals","grade_form","teacher_classes_xml","rooms","times");
$modules['sao'] = array("home","student_grades","stud_info");
$modules['guidance'] = array("home","stud_info","view_referrals");
$modules['administrator'] = array("home","profile","users","exam_sched");
$modules['library'] = array("home","profile");
$modules['scholarship'] = array("student_grades","student_subjects_selector");
$modules['vpacad'] = array("teaching_load");
$modules['misc'] = array('exam_sched');
$modules[''] = array("home","profile");
?>
