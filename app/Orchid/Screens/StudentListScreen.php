<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\StudentListLayout;
use App\Models\Student;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class StudentListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */

     public $name = "Students Record";
    public function query(): array
    {
        return [
            'students' => Student::latest()->paginate(10)
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return 'Student';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All students";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Add')
                ->icon('plus')
                ->route('platform.student.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            StudentListLayout::class
        ];
    }
    
    public function updateStudentStatus(Student $student,Request $request)
    {
        $student->fill(["id"=>$request->get('id'),"status"=>$request->get('status')])->save();

        return redirect()->route('platform.student.list');
    }
    public function deleteStudent(Student $student,Request $request)
    {
        // dd($request);
        $student->delete();

        Alert::info('You have successfully deleted the student.');

        return redirect()->route('platform.student.list');
    }
}