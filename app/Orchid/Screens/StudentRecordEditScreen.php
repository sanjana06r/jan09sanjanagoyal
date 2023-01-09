<?php

namespace App\Orchid\Screens;

use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class StudentRecordEditScreen extends Screen
{
    /**
     * @var StudentRecord
     */
    public $exists = false;
    public $name = '';

    /**
     * Query data.
     *
     * @param StudentRecord $studentrecord
     *
     * @return array
     */
    public function query(StudentRecord $studentrecord): array
    {
        $this->exists = $studentrecord->exists;
        $this->name = $this->name();
        return [
            'studentrecord' => $studentrecord
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return $this->exists ? 'Edit Student Record' : 'Creating a new record for student';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "StudentRecords";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create New')
                ->icon('plus')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
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
            Layout::rows([
                Select::make('studentrecord.student_id')
                    ->fromModel(Student::class, 'email')
                    ->title('Student Email'),
                Select::make('studentrecord.subject_id')
                    ->fromModel(Subject::class, 'name')
                    ->title('Subject Name'),
                Input::make('studentrecord.marks_scored')
                    ->title('Marks'),
                Input::make('studentrecord.grade')
                    ->title('Grade')

            ])
        ];
    }

    /**
     * @param StudentRecord    $studentrecord
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(StudentRecord $studentrecord, Request $request)
    {
        $data = $request->all();
        $validation = Validator::make(
            $request->all(),
            [
                'studentrecord.*' => 'required',
                'studentrecord.student_id' => [
                    'required', 'max:255',
                    function ($attribute, $value, $fail) use ($data,$studentrecord){
                        $exists = StudentRecord::where('subject_id', $data['studentrecord']['subject_id'])->where('student_id',$data['studentrecord']['student_id'])->count();
                        if($studentrecord['id']){
                            if($studentrecord['subject_id'] != $data['studentrecord']['subject_id'] || $studentrecord['student_id'] != $data['studentrecord']['student_id']) {
                                $fail('This student already has subject data');
                            }
                        }elseif($exists){
                            $fail('This student already has subject data');
                        }
                    },
                ],
            ],
        );
        if($validation->fails()){
            return Redirect::back()->withErrors($validation)->withInput();
        }
        $studentrecord->fill($request->get('studentrecord'))->save();

        Alert::info('You have successfully created a studentrecord.');

        return redirect()->route('platform.studentrecord.list');
    }

    /**
     * @param StudentRecord $studentrecord
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(StudentRecord $studentrecord)
    {
        $studentrecord->delete();

        Alert::info('You have successfully deleted the studentrecord.');

        return redirect()->route('platform.studentrecord.list');
    }
}
