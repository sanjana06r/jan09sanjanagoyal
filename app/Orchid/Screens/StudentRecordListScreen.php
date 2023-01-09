<?php

namespace App\Orchid\Screens;

use App\Models\Student;
use App\Orchid\Layouts\StudentRecordListLayout;
use App\Models\StudentRecord;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class StudentRecordListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Students Result Data';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Student $student): array
    {
        if($student->exists){
            return [
                'student_records' => StudentRecord::latest()->where("student_id",$student['id'])->paginate(10)
            ];
        }
        return [
            'student_records' => StudentRecord::latest()->paginate(10)
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Add')
                ->icon('plus')
                ->route('platform.studentrecord.edit')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            StudentRecordListLayout::class
        ];
    }
    public function deleteStudentRecord(StudentRecord $studentrecord,Request $request)
    {
        // dd($request);
        $studentrecord->delete();

        Alert::info('You have successfully deleted the student record.');

        return redirect()->route('platform.studentrecord.list');
    }
}
