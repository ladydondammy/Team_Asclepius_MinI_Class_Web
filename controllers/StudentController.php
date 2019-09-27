<?php

    require_once('./config/database.php') ;


    class Student {

       
        private $db;


        public function __construct(){

            $db = new DB;

            $this->db = $db->get_connection();

        }

        public function ShowCourses($dept_code){

            $courses_sql = "SELECT * FROM courses where dept_code = '$dept_code'";
            $course_query = $this->db->query($courses_sql);
            $courses = mysqli_fetch_assoc($course_query);

            return $courses;

        }


        public function RegisterCourses($courses_array, $id_number){

            $message = '';

            $courses = json_encode($courses_array);

            $insert_sql = "UPDATE users SET courses = '$courses' WHERE id_number = '$id_number'";

            if($insert_query = $this->db->query($insert_sql)){

                $message = "Courses registered  successfully";

            }else{

                $message = "Something went wrong, Please try again";
            }

            return $message;
        }

        public function ShowRegdCourses($id_number){

            $sql = "SELECT * FROM users where id_number = '$id_number'";
            $query = $this->db->query($sql);

            $Result = mysqli_fetch_assoc($query);
            $RegdCourses = $Result['courses'];
            $RegdCourses = json_decode($RegdCourses);

            return $RegdCourses;

        }

        public function ShowCourseNotes($id_number){

            $notes = [];

            $sql = "SELECT * FROM users where id_number = '$id_number'";
            $query = $this->db->query($sql);
            $RegdCourses = mysqli_fetch_assoc($query);

            $courses_offered = $RegdCourses['courses'];
            $courses_offered = json_decode($courses_offered);

            foreach($courses_offered as $course){

                $note_sql = "SELECT * FROM notes where course_code = '$course'";
                $note_query = $this->db->query($note_sql);

                $note = mysqli_fetch_assoc($note_query);
                
                if(!empty($note)){

                    array_push($notes, $note);
                }
                

            }
            return $notes;

        }

        public function ShowCourseAssignments($id_number){

            $assignments = [];

            $sql = "SELECT * FROM users where id_number = '$id_number'";
            $query = $this->db->query($sql);
            $RegdCourses = mysqli_fetch_assoc($query);

            $courses_offered = $RegdCourses['courses'];
            $courses_offered = json_decode($courses_offered);

            foreach($courses_offered as $course){

                $assignment_sql = "SELECT * FROM assignments where course_code = '$course'";
                $assignments_query = $this->db->query($assignment_sql);

                $assignment = mysqli_fetch_assoc($assignments_query);
                
                if(!empty($assignment)){

                    array_push($assignments, $assignment);
                }
                

            }
            return $assignments;

        }

        public function SubmitAssignment($hw,$course_code,$user_id){
            $message='';
            if(getimagesize($_FILES[$hw]['tmp_name'])==FALSE){
                $message='please upload a file.';
            }
            else{
                $created_at=date('y-m-d');
                $ass = $_FILES[$hw]['name'];
                $target="assigment/submit/".basename($ass);
                move_uploaded_file($_FILES[$hw]['tmp_name'],$target);
                try{
                    $insert_sql = "INSERT INTO `submit_assignment` (user_id,course_code,assignment_name,created_at) VALUES('$user_id','$course_code','$ass','$created_at')" ;
                    $insert_query = $this->db->query($insert_sql) or die(mysqli_error($this->db));
                    $message = 'Assignment Succesfully Submited';
                }
                catch (Exception $e){
                    $message = 'Something Went wrong. Please try again.';
                }


            }
            return $message;
        }

        public function ShowAssignmentToLecturer($course_code){

            $sql = "SELECT assignment_name FROM submit_assignment where course = '$course_code'";
            $query = $this->db->query($sql);
            $Courses = mysqli_fetch_assoc($query);
            $courses = $Courses['course_code'];
            $allcourse=[];

            foreach ($courses as $item){
                array_push($allcourse,$item);
            }
            return $allcourse;

        }

        public function ShowAssignmentToStudent($user_id){

            $sql = "SELECT assignment_name FROM submit_assignment where user_id = '$user_id'";
            $query = $this->db->query($sql);
            $Courses = mysqli_fetch_assoc($query);
            $course = $Courses['course_code'];

            return $course;

        }

        public function GetUser($user_id){

            $sql = "SELECT * FROM users where user_id = '$user_id'";
            $query =$this->db->query($sql);

            $user = mysqli_fetch_assoc($query);

            return $user;
          }

        public function SelectCourses($faculty){

            $sql ="SELECT * FROM courses WHERE faculty_code = '$faculty'";
            $fac_courses = $this->db->query($sql);

            return $fac_courses;

        }

    }



?>