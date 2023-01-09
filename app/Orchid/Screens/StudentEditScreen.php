<?php

namespace App\Orchid\Screens;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class StudentEditScreen extends Screen
{
    /**
     * @var Student
     */
    public $exists = false;
    public $name = '';

    /**
     * Query data.
     *
     * @param Student $student
     *
     * @return array
     */
    public function query(Student $student): array
    {
        $this->exists = $student->exists;
        $this->name = $this->name();
        return [
            'student' => $student
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return $this->exists ? 'Edit student' : 'Creating a new student';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Students";
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
                Input::make('student.name')
                    ->type('text')
                    ->max(255)
                    ->title(__('Name'))
                    ->placeholder(__('Enter Student Name')),

                Input::make('student.email')
                    ->type('email')
                    ->title(__('Email'))
                    ->placeholder(__('Enter Student Email')),
                Input::make('student.phone')
                    ->mask('(999) 999-9999')
                    ->type('tel')
                    ->title('Phone')
                    ->placeholder('Enter Student Phone'),
                Input::make('student.address')
                    ->title('Address')
                    ->placeholder('Enter Student Address'),
                Input::make('student.city')
                    ->title('City')
                    ->placeholder('Enter Student City'),
                Input::make('student.state')
                    ->title('State')
                    ->placeholder('Enter Student State'),
                Input::make('student.country')
                    ->title('Country')
                    ->placeholder('Enter Student Country'),
                Select::make('student.status')
                    ->options([
                        '1'   => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->title('Status'),

            ])
        ];
    }

    /**
     * @param Student    $student
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Student $student, Request $request)
    {
        $data = $request->all();
        $validation = Validator::make(
            $request->all(),
            [
                'student.*' => 'required',
                'student.email' => [
                    'required', 'max:255',
                    function ($attribute, $value, $fail) use ($data,$student){
                        $exists = Student::where('email', $data['student']['email'])->count();
                        if($student['id']){
                            if($student['email'] != $data['student']['email'] && $exists) {
                                $fail('Student Email already taken');
                            }
                        }elseif($exists){
                            $fail('Student Email already taken');
                        }
                    },
                ],
            ],
        );
        if($validation->fails()){
            return Redirect::back()->withErrors($validation)->withInput();
        }
        $student->fill($request->get('student'))->save();

        Alert::info('You have successfully created a student.');

        return redirect()->route('platform.student.list');
    }

    /**
     * @param Student $student
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Student $student)
    {
        $student->studentrecords()->delete();
        $student->delete(); 

        Alert::info('You have successfully deleted the student.');

        return redirect()->route('platform.student.list');
    }
}
