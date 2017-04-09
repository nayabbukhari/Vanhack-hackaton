<?php
namespace Thinkific\Controllers\Course;
use Thinkific\Controllers\Controller;
use Thinkific\Models\Course as CourseModel;
use Thinkific\Models\CourseMeta;

/**
 * Created by Thiago Alencar.
 * User: thiagoalencar
 * Date: 08/04/17
 * Time: 17:59
 */
class CourseController extends Controller
{
    public function new($request, $response){
        $container = $this->getContainer();
        $user = $container->authentication->getUser();
        $params = $request->getParams();

        $course = CourseModel::create([
                'user_id' => $user["id"],
                'title' => 'My Course',
                'mudule_id' => 1
        ]);

        $atributes = $course->getAttributes();

        $redirect =  sprintf("http://%s/%s/courses",$_SERVER["HTTP_HOST"], $atributes["id"]);
        header("Location: $redirect");
        exit();
    }

    public function view($request, $response, $args){

        if (isset($_SERVER["QUERY_STRING"])){
            $courseMeta = CourseMeta::create([
                'course_id' => $args["id"],
                'module_id' => 1,
                'key'       => 'query_string',
                'value'     => $_SERVER["QUERY_STRING"]
            ]);
        } else {
            $courseMeta = CourseMeta::where('course_id', $args["id"])->where('key','query_string')->first();
            if(isset($courseMeta->value)){
                $redirect = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PATH_INFO"] . "?" . $courseMeta->value;
                echo $redirect;
                header("Location: $redirect");
                exit();
            }
        }

        return $this->getView()->render($response, 'Course/new.twig');
    }
}